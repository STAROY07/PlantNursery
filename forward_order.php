<?php
session_start();
include 'admin_db_connection.php';

/* 🔒 CHECK ADMIN LOGIN (ADDED – SAFE) */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = $_GET['id'];

/* 🔍 GET CURRENT STATUS + PAYMENT STATUS (SMALL ADDITION) */
$result = mysqli_query(
    $conn,
    "SELECT status, payment_status FROM orders WHERE id='$order_id'"
);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: manage_orders.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

/* 🚫 DO NOT MOVE FORWARD IF PAYMENT NOT DONE */
if ($row['payment_status'] != 'paid' && $row['payment_status'] != 'cod') {
    header("Location: manage_orders.php");
    exit();
}

/* ✅ YOUR ORIGINAL STATUS FLOW (UNCHANGED) */
$statuses = [
    "Order Placed",
    "Processing",
    "Shipped",
    "Out for Delivery",
    "Delivered"
];

$currentIndex = array_search($row['status'], $statuses);

/* Move to next stage if not Delivered */
if ($currentIndex !== false && $currentIndex < count($statuses) - 1) {
    $nextStatus = $statuses[$currentIndex + 1];
    mysqli_query(
        $conn,
        "UPDATE orders SET status='$nextStatus' WHERE id='$order_id'"
    );
}

header("Location: manage_orders.php");
exit();
