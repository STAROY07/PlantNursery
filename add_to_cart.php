<?php
session_start();
include 'db_connect.php';

/* 🔐 USER LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id  = (int)$_SESSION['user_id'];
$plant_id = (int)($_POST['plant_id'] ?? 0);

/* ❌ INVALID REQUEST */
if ($plant_id <= 0) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

/* ✅ FETCH PLANT DETAILS */
$sql = "SELECT id, name, price, image 
        FROM plants 
        WHERE id = '$plant_id' 
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$plant = mysqli_fetch_assoc($result);

/* 🛒 CHECK IF PLANT ALREADY IN CART */
$check = mysqli_query(
    $conn,
    "SELECT id FROM cart 
     WHERE user_id = '$user_id' 
     AND plant_id = '{$plant['id']}'"
);

if (mysqli_num_rows($check) > 0) {

    /* ➕ Increase quantity */
    mysqli_query(
        $conn,
        "UPDATE cart 
         SET quantity = quantity + 1 
         WHERE user_id = '$user_id' 
         AND plant_id = '{$plant['id']}'"
    );

} else {

    /* ➕ Insert new cart item */
    mysqli_query(
        $conn,
        "INSERT INTO cart 
        (user_id, plant_id, plant_name, price, image, quantity)
        VALUES (
            '$user_id',
            '{$plant['id']}',
            '".mysqli_real_escape_string($conn, $plant['name'])."',
            '{$plant['price']}',
            '".mysqli_real_escape_string($conn, $plant['image'])."',
            1
        )"
    );
}

/* 🧹 REMOVE FROM WISHLIST IF ADDED FROM THERE */
if (isset($_POST['from_wishlist']) && $_POST['from_wishlist'] == '1') {
    mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = '$user_id' AND plant_id = '{$plant['id']}'");
}

/* ✅ SET SUCCESS MESSAGE */
$_SESSION['cart_msg'] = $plant['name'] . " added to cart successfully!";

/* 🔁 REDIRECT BACK TO SAME PAGE */
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>