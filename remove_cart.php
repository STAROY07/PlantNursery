<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$cart_id = (int)$_POST['cart_id'];

mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id");

header("Location: cart.php");
exit();
