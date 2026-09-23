<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_id'])) {
    header("Location: cart.php");
    exit();
}

$user_id  = $_SESSION['user_id'];
$order_id = $_SESSION['order_id'];

/* 🔹 GET RAZORPAY DATA */
$transaction_id = $_POST['razorpay_payment_id'] ?? '';
$amount         = $_POST['amount'] ?? 0;

if ($transaction_id == '') {
    die("Payment Failed");
}

/* 🔹 UPDATE ORDER */
mysqli_query($conn, "
    UPDATE orders SET
        payment_method = 'Razorpay',
        payment_status = 'Paid',
        status = 'Order Placed'
    WHERE id = $order_id
");

/* 🔹 INSERT PAYMENT RECORD */
mysqli_query($conn, "
    INSERT INTO payments
    (order_id, amount, payment_method, payment_date, status, transaction_id)
    VALUES
    ($order_id, $amount, 'Razorpay', NOW(), 'Paid', '$transaction_id')
");

/* 🔹 CLEAR CART */
mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

unset($_SESSION['payment_pending']);
unset($_SESSION['order_id']);

header("Location: order_success.php");
exit();
?>