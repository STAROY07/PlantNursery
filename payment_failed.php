<?php
session_start();
include "db_connect.php";

/* 🔒 BASIC SECURITY */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* 🔁 MARK LAST ORDER AS FAILED (IF EXISTS) */
if (isset($_SESSION['current_order_id'])) {

    $order_id = $_SESSION['current_order_id'];

    mysqli_query($conn, "
        UPDATE orders SET
            payment_status = 'failed',
            status = 'Payment Failed'
        WHERE id = '$order_id'
    ");

    unset($_SESSION['current_order_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Failed</title>

<style>
body {
    height: 100vh;
    background: linear-gradient(135deg, #ffebee, #fce4ec);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.fail-card {
    background: #ffffff;
    width: 420px;
    padding: 35px;
    border-radius: 22px;
    text-align: center;
    box-shadow: 0 30px 80px rgba(0,0,0,0.15);
}
.fail-icon {
    font-size: 60px;
    color: #e74c3c;
}
.fail-card h2 {
    color: #e74c3c;
    margin: 15px 0;
}
.fail-card p {
    margin: 8px 0;
    color: #555;
}
.btn-group {
    margin-top: 25px;
}
.btn-group a {
    display: inline-block;
    margin: 5px;
    padding: 12px 22px;
    border-radius: 30px;
    text-decoration: none;
    color: white;
    background: #e74c3c;
}
.btn-group a.secondary {
    background: #555;
}
</style>
</head>

<body>

<div class="fail-card">
    <div class="fail-icon">❌</div>
    <h2>Payment Failed</h2>
    <p>Your payment could not be completed.</p>
    <p>Please try again or choose Cash on Delivery.</p>

    <div class="btn-group">
        <a href="payment.php">Try Again</a>
        <a href="cart.php" class="secondary">Back to Cart</a>
    </div>
</div>

</body>
</html>
