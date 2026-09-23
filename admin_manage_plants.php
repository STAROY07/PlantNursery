<?php
session_start();
include 'admin_db_connection.php';

/* 🔒 CHECK ADMIN LOGIN */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();

}


if (isset($_GET['search']) && !empty($_GET['search'])) {
    
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    $query = "SELECT * FROM plants 
              WHERE name LIKE '%$search%' 
              ORDER BY id DESC";

} else {

    $query = "SELECT * FROM plants ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Plants</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
    padding: 30px;
}

/* Title */
.page-title {
    text-align: center;
    color: #2e7d32;
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 25px;
}

/* Add Button */
a.add {
    display: inline-block;
    padding: 10px 18px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 8px 20px rgba(39,174,96,0.3);
    transition: 0.3s ease;
}

a.add:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(39,174,96,0.45);
}

/* Search Bar */
form {
    margin: 20px 0;
}

input[type="text"] {
    padding: 10px 15px;
    width: 260px;
    border-radius: 30px;
    border: 1px solid #ccc;
    outline: none;
    transition: 0.3s;
}

input[type="text"]:focus {
    border-color: #27ae60;
    box-shadow: 0 0 8px rgba(39,174,96,0.3);
}

button {
    padding: 9px 18px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

button[type="submit"] {
    background: #2e7d32;
    color: white;
}

button[type="submit"]:hover {
    background: #1b5e20;
}

a[href="admin_manage_plants.php"] {
    padding: 9px 18px;
    border-radius: 30px;
    background: #9e9e9e;
    color: white;
    text-decoration: none;
    font-weight: 600;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
}

th {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: white;
    padding: 15px;
    font-size: 14px;
}

td {
    padding: 14px;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f4fff7;
}

/* Buttons */
a.edit {
    padding: 6px 14px;
    background: #3498db;
    color: white;
    border-radius: 20px;
    text-decoration: none;
    font-size: 13px;
}

a.delete {
    padding: 6px 14px;
    background: #e74c3c;
    color: white;
    border-radius: 20px;
    text-decoration: none;
    font-size: 13px;
}

/* Image */
img {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
}
</style>
</head>
<body>

<h2 class="page-title">Manage Plants</h2>
<a class="btn add" href="admin_add_plant.php">+ Add Plant</a>
<br><br>
<form method="GET" style="margin:15px 0;">
    <input type="text" name="search" placeholder="Search plant by name..."
        value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>"
        style="padding:8px; width:250px; border-radius:5px; border:1px solid #ccc;">
    
    <button type="submit"
        style="padding:8px 15px; background:#2b7a2b; color:white; border:none; border-radius:5px;">
        Search
    </button>

    <a href="admin_manage_plants.php"
        style="padding:8px 15px; background:#999; color:white; text-decoration:none; border-radius:5px;">
        Reset
    </a>
</form>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Image</th>
<th>Category</th>
<th>Stock</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td>₹<?= $row['price'] ?></td>
<td><img src="<?= $row['image'] ?>"></td>
<td><?= $row['category'] ?></td>
<td><?= $row['stock'] ?></td>
<td>
<a class="btn edit" href="admin_edit_plant.php?id=<?= $row['id'] ?>">Edit</a>
<a class="btn delete" href="admin_delete_plant.php?id=<?= $row['id'] ?>" 
onclick="return confirm('Delete this plant?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>