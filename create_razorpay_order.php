<?php
session_start();
require('razorpay-php/Razorpay.php');
include 'db_connect.php';

header('Content-Type: application/json');

use Razorpay\Api\Api;

/* 🔒 CHECK ORDER SESSION */
if (!isset($_SESSION['order_id'])) {
    echo json_encode(['error' => 'Session order_id missing']);
    exit();
}

$order_id = intval($_SESSION['order_id']);

/* 🔎 FETCH ORDER AMOUNT */
$order_q = mysqli_query($conn, "SELECT total_amount FROM orders WHERE id = $order_id");

if (!$order_q || mysqli_num_rows($order_q) == 0) {
    echo json_encode(['error' => 'Invalid Order ID']);
    exit();
}

$order_data = mysqli_fetch_assoc($order_q);

$amount = intval($order_data['total_amount']) * 100; // convert to paise

if ($amount <= 0) {
    echo json_encode(['error' => 'Invalid amount']);
    exit();
}

/* 🔑 Razorpay Test Keys */
include 'razorpay_config.php';
$keyId = $razorpay_key_id;
$keySecret = $razorpay_key_secret;

try {

    $api = new Api($keyId, $keySecret);

    $razorpayOrder = $api->order->create([
        'receipt' => 'order_' . $order_id,
        'amount' => $amount,
        'currency' => 'INR'
    ]);

    echo json_encode([
        'success' => true,
        'key' => $keyId,
        'amount' => $amount,
        'order_id' => $razorpayOrder['id']
    ]);

} catch (Exception $e) {

    echo json_encode([
        'error' => 'Razorpay Error: ' . $e->getMessage()
    ]);
}
