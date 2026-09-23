<?php
session_start();
include 'admin_db_connection.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['add_plant'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = $_POST['stock'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $image_name = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];
    $folder = "Images/" . $image_name;

    move_uploaded_file($temp_name, $folder);

    mysqli_query($conn, "
        INSERT INTO plants (name, description, price, image, category, stock)
        VALUES ('$name', '$description', '$price', '$folder', '$category', '$stock')
    ");

    header("Location: admin_manage_plants.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Plant</title>

<style>
body {
    font-family: Arial;
    background: #f0f9f0;
}

.container {
    width: 450px;
    margin: 60px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #2e7d32;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button {
    width: 100%;
    padding: 12px;
    background: #2e7d32;
    color: white;
    border: none;
    border-radius: 25px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #1b5e20;
}
</style>
</head>

<body>

<div class="container">
<h2>🌱 Add New Plant</h2>

<form method="POST" enctype="multipart/form-data">

Name:
<input type="text" name="name" required>

Price:
<input type="number" step="0.01" name="price" required>

Category:
<input type="text" name="category" required>

Stock:
<input type="number" name="stock" required>

Description:
<textarea name="description"></textarea>

Image:
<input type="file" name="image" required>

<button type="submit" name="add_plant">Add Plant</button>

</form>
</div>

</body>
</html>