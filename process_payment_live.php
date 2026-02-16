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

// Generate sequential order number APU-001, APU-002, etc
$counterFile = 'order_counter.txt';
$counter = file_exists($counterFile) ? (int)file_get_contents($counterFile) : 0;
$counter++;
file_put_contents($counterFile, $counter);
$orderReference = 'APU-' . str_pad($counter, 3, '0', STR_PAD_LEFT);

// Calculate delivery price (total minus cart items)
$cartSubtotal = 0;
foreach ($cart as $item) {
    $cartSubtotal += $item['price'] * $item['qty'];
}
$deliveryPrice = $total - $cartSubtotal;

// Build WhatsApp message
$whatsappMsg = "*NEW ORDER*\n\n";
$whatsappMsg .= "*Order Number:* " . $orderReference . "\n\n";

$whatsappMsg .= "*Items:*\n";
foreach ($cart as $item) {
    $whatsappMsg .= $item['qty'] . "x " . $item['name'] . " — R" . number_format($item['price'] * $item['qty'], 2) . "\n";
}

$whatsappMsg .= "\n*Delivery Price:* R" . number_format($deliveryPrice, 2) . "\n";
$whatsappMsg .= "*TOTAL TO PAY:* R" . number_format($total, 2) . "\n\n";

$whatsappMsg .= "*Customer Info:*\n";
$whatsappMsg .= "Name: " . ($customer['name'] ?? 'N/A') . "\n";
$whatsappMsg .= "Phone: " . ($customer['phone'] ?? 'N/A') . "\n";
$whatsappMsg .= "Address: " . ($customer['address'] ?? 'N/A') . "\n\n";

$whatsappMsg .= "Thank you for choosing us " . ($customer['name'] ?? 'valued customer') . ", we will send you the payment link shortly!\n\n";
$whatsappMsg .= "Kind regards\nAPU-MED Team";

// Return success response with WhatsApp link
echo json_encode([
    'success' => true,
    'order_id' => $orderReference,
    'whatsapp_url' => 'https://wa.me/' . $SELLER_WHATSAPP . '?text=' . urlencode($whatsappMsg),
    'whatsapp_message' => $whatsappMsg,
    'payment_link' => $YOCO_PAYMENT_LINK
]);
?>

