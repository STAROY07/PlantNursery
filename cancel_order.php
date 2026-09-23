<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

/* Check order belongs to user */
$order_query = mysqli_query($conn, 
    "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'"
);

$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    die("Invalid Order");
}

/* Allow cancel only if Pending or Order Placed */
if ($order['status'] != 'Pending' && $order['status'] != 'Order Placed') {
    die("Order cannot be cancelled after shipping.");
}

/* If Razorpay and Paid → mark as Refunded */
if ($order['payment_method'] == 'Razorpay' && $order['payment_status'] == 'paid') {

    mysqli_query($conn, "
        UPDATE orders 
        SET status='Cancelled', payment_status='Refunded'
        WHERE id='$order_id'
    ");
    mysqli_query($conn,
    "UPDATE payments 
     SET status='Refunded' 
     WHERE order_id='$order_id'"
);

} else {

    mysqli_query($conn, "
        UPDATE orders 
        SET status='Cancelled'
        WHERE id='$order_id'
    ");
}

header("Location: orders.php");
exit();
?>