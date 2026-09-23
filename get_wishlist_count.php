<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["count" => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];
$count_res = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id='$user_id'");
$count = mysqli_num_rows($count_res);

echo json_encode(["count" => $count]);
exit();
?>
