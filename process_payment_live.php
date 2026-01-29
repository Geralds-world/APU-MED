<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$SELLER_WHATSAPP = '27723356685';
$YOCO_PAYMENT_LINK = 'https://pay.yoco.com/apocalypse-unit';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cart']) || !isset($data['total']) || !isset($data['customer'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$cart = $data['cart'];
$total = $data['total'];
$customer = $data['customer'];
$deliveryInfo = $data['deliveryInfo'] ?? [];
$orderReference = 'APU-' . time() . '-' . rand(1000, 9999);

// Build WhatsApp message with Yoco payment link
$whatsappMsg = "🆘 *NEW APU ORDER - PAYMENT REQUIRED*\n";
$whatsappMsg .= "Order ID: " . $orderReference . "\n";
$whatsappMsg .= "------------------\n";

foreach ($cart as $item) {
    $whatsappMsg .= $item['qty'] . "x " . $item['name'] . " — R" . number_format($item['price'] * $item['qty'], 2) . "\n";
}

$whatsappMsg .= "------------------\n";
$whatsappMsg .= "*TOTAL TO PAY:* R" . number_format($total, 2) . "\n\n";

$whatsappMsg .= "📋 *CUSTOMER INFO*\n";
$whatsappMsg .= "Name: " . ($customer['name'] ?? 'N/A') . "\n";
$whatsappMsg .= "Phone: " . ($customer['phone'] ?? 'N/A') . "\n";
$whatsappMsg .= "Address: " . ($customer['address'] ?? 'N/A') . "\n";

if (!empty($deliveryInfo)) {
    $whatsappMsg .= "\n📦 " . $deliveryInfo;
}

$whatsappMsg .= "\n\n💳 *PAYMENT LINK:*\n" . $YOCO_PAYMENT_LINK . "\n";
$whatsappMsg .= "\n⏳ Please pay and reply with proof of payment. Thanks!";

// Return success response with WhatsApp link
echo json_encode([
    'success' => true,
    'order_id' => $orderReference,
    'whatsapp_url' => 'https://wa.me/' . $SELLER_WHATSAPP . '?text=' . urlencode($whatsappMsg),
    'whatsapp_message' => $whatsappMsg,
    'payment_link' => $YOCO_PAYMENT_LINK
]);
?>

