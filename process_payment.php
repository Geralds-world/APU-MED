<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Your Yoco credentials
$YOCO_SECRET_KEY = 'sk_test_4e79d50bW4oN8bAf18848c69f071';
$SELLER_WHATSAPP = '27723356685'; // Your WhatsApp number (international format)

// Handle preflight requests
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

// Validate required fields
if (!isset($data['cart']) || !isset($data['total']) || !isset($data['customer'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$cart = $data['cart'];
$total = $data['total'];
$customer = $data['customer'];
$deliveryInfo = $data['deliveryInfo'] ?? [];

// Convert to cents (Yoco uses cents)
$amountInCents = round($total * 100);

// Create a unique reference for this order
$orderReference = 'APU-' . time() . '-' . rand(1000, 9999);

// Prepare the payment request for Yoco API
$paymentData = [
    'amount' => $amountInCents,
    'currency' => 'ZAR',
    'metadata' => [
        'order_id' => $orderReference,
        'customer_name' => $customer['name'] ?? '',
        'customer_phone' => $customer['phone'] ?? '',
        'customer_address' => $customer['address'] ?? '',
    ]
];

// Call Yoco API to create payment request
$ch = curl_init('https://api.yoco.com/v1/payment_requests');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $YOCO_SECRET_KEY
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 201 && $httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create payment request', 'details' => $response]);
    exit();
}

$paymentResponse = json_decode($response, true);

// Build WhatsApp message for confirmation
$whatsappMsg = "🆘 *NEW APU ORDER - PAYMENT PENDING*\n";
$whatsappMsg .= "Order ID: " . $orderReference . "\n";
$whatsappMsg .= "------------------\n";

foreach ($cart as $item) {
    $whatsappMsg .= $item['qty'] . "x " . $item['name'] . " — R" . number_format($item['price'] * $item['qty'], 2) . "\n";
}

$whatsappMsg .= "------------------\n";
$whatsappMsg .= "*TOTAL:* R" . number_format($total, 2) . "\n\n";

$whatsappMsg .= "📋 *CUSTOMER INFO*\n";
$whatsappMsg .= "Name: " . ($customer['name'] ?? 'N/A') . "\n";
$whatsappMsg .= "Phone: " . ($customer['phone'] ?? 'N/A') . "\n";
$whatsappMsg .= "Address: " . ($customer['address'] ?? 'N/A') . "\n";
$whatsappMsg .= "Postal Code: " . ($customer['postal'] ?? 'N/A') . "\n";
$whatsappMsg .= "Area: " . ($customer['area'] ?? 'N/A') . "\n\n";

if (!empty($deliveryInfo)) {
    $whatsappMsg .= "📦 " . $deliveryInfo . "\n\n";
}

$whatsappMsg .= "⏳ *AWAITING PAYMENT CONFIRMATION*\n";
$whatsappMsg .= "Customer is completing payment...";

// Return the payment link to frontend
echo json_encode([
    'success' => true,
    'order_id' => $orderReference,
    'payment_url' => $paymentResponse['links']['payment_page'] ?? null,
    'amount' => $total,
    'whatsapp_message' => $whatsappMsg
]);
?>
