<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please login first."]);
    exit();
}

if (!isset($_POST['plant_id']) || $_POST['plant_id'] === '') {
    echo json_encode(["status" => "error", "message" => "Invalid plant request"]);
    exit();
}

$user_id  = $_SESSION['user_id'];
$plant_id = (int) $_POST['plant_id'];

if ($plant_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid plant request"]);
    exit();
}

/* Prevent duplicate entry */
$check = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id='$user_id' AND plant_id='$plant_id'");

if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO wishlist (user_id, plant_id) VALUES ('$user_id','$plant_id')");
    $added = true;
} else {
    $added = false;
}

// Get new count
$count_res = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id='$user_id'");
$count = mysqli_num_rows($count_res);

echo json_encode([
    "status" => "success", 
    "message" => $added ? "Added to wishlist!" : "Already in wishlist!",
    "count" => $count
]);
exit();
?>
