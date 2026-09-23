<?php
session_start();
include "db_connect.php";

/* 🔒 SECURITY CHECK */
if (!isset($_SESSION['order_id']) || !isset($_SESSION['user_id'])) {
    header("Location: cart.php");
    exit();
}

$order_id = $_SESSION['order_id'];
$user_id  = $_SESSION['user_id'];

/* ✅ UPDATE ORDER FOR COD */
mysqli_query($conn, "
    UPDATE orders SET
        payment_method = 'COD',
        payment_status = 'Pending',
        status = 'Order Placed'
    WHERE id = '$order_id'
");

/* 💳 INSERT PAYMENT RECORD */
$res = mysqli_query($conn, "SELECT total_amount FROM orders WHERE id='$order_id'");
$row = mysqli_fetch_assoc($res);

mysqli_query($conn, "
    INSERT INTO payments (order_id, amount, payment_date, status)
    VALUES ('$order_id', '".$row['total_amount']."', NOW(), 'COD')
");

/* 📦 MOVE CART ITEMS TO order_items */
$cart_q = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");

while ($item = mysqli_fetch_assoc($cart_q)) {
    mysqli_query($conn, "
        INSERT INTO order_items (order_id, plant_name, price, quantity)
        VALUES (
            '$order_id',
            '{$item['plant_name']}',
            '{$item['price']}',
            '{$item['quantity']}'
        )
    ");
}

/* 🧹 CLEAR CART */
mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

/* 🧹 CLEAN SESSION */
unset($_SESSION['order_id']);
unset($_SESSION['payment_pending']);

/* 🎉 SUCCESS */
header("Location: payment_success.php");
exit();
?>
