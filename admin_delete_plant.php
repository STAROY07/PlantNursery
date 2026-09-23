<?php
session_start();
include 'db_connect.php';

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM plants WHERE id='$id'");

header("Location: admin_manage_plants.php");
exit();
?>