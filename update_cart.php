<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* Validate request */
if (!isset($_POST['cart_id'], $_POST['action'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = (int) $_POST['cart_id'];
$action  = $_POST['action'];

/* Make sure cart item belongs to this user */
$check = mysqli_query(
    $conn,
    "SELECT quantity 
     FROM cart 
     WHERE id = $cart_id 
       AND user_id = $user_id"
);

if (mysqli_num_rows($check) == 0) {
    header("Location: cart.php");
    exit();
}

$row = mysqli_fetch_assoc($check);

if ($action === 'increase') {

    mysqli_query(
        $conn,
        "UPDATE cart 
         SET quantity = quantity + 1 
         WHERE id = $cart_id 
           AND user_id = $user_id"
    );

} elseif ($action === 'decrease') {

    if ($row['quantity'] > 1) {
        mysqli_query(
            $conn,
            "UPDATE cart 
             SET quantity = quantity - 1 
             WHERE id = $cart_id 
               AND user_id = $user_id"
        );
    } else {
        // quantity == 1 → remove item
        mysqli_query(
            $conn,
            "DELETE FROM cart 
             WHERE id = $cart_id 
               AND user_id = $user_id"
        );
    }
}

header("Location: cart.php");
exit();
