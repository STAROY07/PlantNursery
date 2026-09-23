<?php
session_start();
include 'admin_db_connection.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* GET FILTER VALUES */
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

/* QUERY */
$sql = "SELECT * FROM plants WHERE 1";

if ($search != '') {
    $sql .= " AND name LIKE '%$search%'";
}

if ($category != '') {
    $sql .= " AND category='$category'";
}

$sql .= " ORDER BY id ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Plants</title>
<style>

body{
    font-family: Arial;
    background:#f2f4f3;
    margin:0;
}

.container{
    width:95%;
    margin:30px auto;
}

h2{
    margin-bottom:15px;
}

.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.filter-box{
    display:flex;
    gap:10px;
}

input, select{
    padding:8px;
    border-radius:5px;
    border:1px solid #ccc;
}

button{
    padding:8px 15px;
    background:#3498db;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.add-btn{
    background:#2ecc71;
    padding:8px 15px;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#2ecc71;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

img{
    width:60px;
    border-radius:6px;
}

.edit{
    background:#3498db;
    padding:5px 10px;
    color:white;
    text-decoration:none;
    border-radius:4px;
    font-size:12px;
}

.delete{
    background:#e74c3c;
    padding:5px 10px;
    color:white;
    text-decoration:none;
    border-radius:4px;
    font-size:12px;
}

</style>
</head>

<body>

<div class="container">

<h2>Manage Plants</h2>

<div class="top-bar">

<form method="GET" class="filter-box">

    <!-- SEARCH -->
    <input type="text" name="search"
           placeholder="Search plant name..."
           value="<?= htmlspecialchars($search) ?>">

    <!-- CATEGORY -->
    <select name="category">
        <option value="">All Categories</option>
        <?php
        $cat_query = mysqli_query($conn,"SELECT DISTINCT category FROM plants");
        while($cat = mysqli_fetch_assoc($cat_query)){
            $selected = ($category == $cat['category']) ? "selected" : "";
            echo "<option value='{$cat['category']}' $selected>
                    {$cat['category']}
                  </option>";
        }
        ?>
    </select>

    <button type="submit">Filter</button>

</form>

<a href="admin_add_plant.php" class="add-btn">+ Add Plant</a>

</div>

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
        <a href="admin_edit_plant.php?id=<?= $row['id'] ?>" class="edit">Edit</a>
        <a href="admin_delete_plant.php?id=<?= $row['id'] ?>" 
           class="delete"
           onclick="return confirm('Delete this plant?')">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>