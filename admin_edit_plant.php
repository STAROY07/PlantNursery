<?php
session_start();
include 'admin_db_connection.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM plants WHERE id=$id");
$plant = mysqli_fetch_assoc($result);

if (isset($_POST['update_plant'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = $_POST['stock'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $temp_name = $_FILES['image']['tmp_name'];
        $folder = "Images/" . $image_name;
        move_uploaded_file($temp_name, $folder);
    } else {
        $folder = $plant['image'];
    }

    mysqli_query($conn, "
        UPDATE plants SET
        name='$name',
        description='$description',
        price='$price',
        image='$folder',
        category='$category',
        stock='$stock'
        WHERE id=$id
    ");

    header("Location: admin_manage_plants.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Plant</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
    margin: 0;
}

.container {
    width: 500px;
    margin: 60px auto;
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.12);
}

h2 {
    text-align: center;
    color: #2e7d32;
    margin-bottom: 25px;
}

label {
    font-weight: 600;
    font-size: 14px;
    color: #333;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 14px;
}

textarea {
    resize: none;
    height: 80px;
}

.current-image {
    text-align: center;
    margin-bottom: 15px;
}

.current-image img {
    width: 120px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    border-radius: 30px;
    font-weight: bold;
    cursor: pointer;
    font-size: 15px;
    box-shadow: 0 12px 30px rgba(39,174,96,0.4);
    transition: 0.3s;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(39,174,96,0.6);
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #2e7d32;
    text-decoration: none;
    font-size: 14px;
}
</style>
</head>

<body>

<div class="container">

<h2>🌿 Edit Plant</h2>

<form method="POST" enctype="multipart/form-data">

<label>Name</label>
<input type="text" name="name" value="<?= $plant['name'] ?>" required>

<label>Price</label>
<input type="number" step="0.01" name="price" value="<?= $plant['price'] ?>" required>

<label>Category</label>
<input type="text" name="category" value="<?= $plant['category'] ?>" required>

<label>Stock</label>
<input type="number" name="stock" value="<?= $plant['stock'] ?>" required>

<label>Description</label>
<textarea name="description"><?= $plant['description'] ?></textarea>

<div class="current-image">
    <label>Current Image</label><br>
    <img src="<?= $plant['image'] ?>">
</div>

<label>Change Image</label>
<input type="file" name="image">

<button type="submit" name="update_plant">Update Plant</button>

</form>

<a href="admin_manage_plants.php" class="back-link">← Back to Manage Plants</a>

</div>

</body>
</html>