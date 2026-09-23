<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['plant_id']) || $_POST['plant_id'] === '') {
    die("Invalid plant request");
}

$user_id  = $_SESSION['user_id'];
$plant_id = (int) $_POST['plant_id'];

if ($plant_id <= 0) {
    die("Invalid plant request");
}

/* Prevent duplicate entry */
$check = mysqli_query($conn,
"SELECT id FROM wishlist WHERE user_id='$user_id' AND plant_id='$plant_id'");

if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn,
    "INSERT INTO wishlist (user_id, plant_id)
     VALUES ('$user_id','$plant_id')");
}

header("Location: wishlist.php");
exit();
