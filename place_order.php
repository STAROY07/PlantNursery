<?php
session_start();
include 'db_connect.php';

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* FORM CHECK */
if (!isset($_POST['confirm_order'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
echo $user_id;


/* FETCH USER ADDRESS */
$user_q = mysqli_query($conn, "SELECT address, phone FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_q);

if (empty($user['address']) || empty($user['phone'])) {
    $_SESSION['error'] = "Please update your delivery address and phone first!";
    header("Location: cart.php");
    exit();
}

/* CALCULATE CART TOTAL */
$total = 0;
$cart_q = mysqli_query($conn, "SELECT price, quantity FROM cart WHERE user_id='$user_id'");

while ($row = mysqli_fetch_assoc($cart_q)) {
    $total += $row['price'] * $row['quantity'];
}

if ($total <= 0) {
    header("Location: cart.php");
    exit();
}

/* INSERT ORDER */
mysqli_query($conn, "
INSERT INTO orders (
    user_id,
    total_amount,
    delivery_address,
    phone,
    order_date,
    status,
    payment_method,
    payment_status
) VALUES (
    '$user_id',
    '$total',
    '".mysqli_real_escape_string($conn,$user['address'])."',
    '".mysqli_real_escape_string($conn,$user['phone'])."',
    NOW(),
    'Pending',
    'Not Selected',
    'Unpaid'
)
");
/* SAVE ORDER ID */
$order_id = mysqli_insert_id($conn);
$_SESSION['last_order_id'] = $order_id;

/* ================= STOCK REDUCTION DISABLED (Unlimited Stock) ================= */
// Stock is unlimited — no check or reduction needed.

/* SAVE SESSION FOR PAYMENT PAGE */
$_SESSION['order_id'] = $order_id;
$_SESSION['payment_pending'] = true;

/* REDIRECT TO PAYMENT PAGE */
header("Location: payment.php");
exit();?>
