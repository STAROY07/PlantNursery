<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit();
}

$user_id  = $_SESSION['user_id'];
$order_id = (int) $_GET['order_id'];

/* 🔒 Verify order belongs to user */
$check = mysqli_query($conn, "
    SELECT id 
    FROM orders 
    WHERE id = $order_id 
      AND user_id = $user_id
");

if (mysqli_num_rows($check) == 0) {
    die("Unauthorized access");
}

/* ✅ Fetch order items */
$query = "
SELECT
    p.name,
    p.image,
    oi.price,
    oi.quantity,
    (oi.price * oi.quantity) AS total
FROM order_items oi
JOIN plants p ON oi.plant_id = p.id
WHERE oi.order_id = $order_id
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details</title>

<style>
/* 🌿 GLOBAL */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    min-height: 100vh;
    background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
    padding: 40px 20px;
}

/* 📦 TITLE */
.page-title {
    text-align: center;
    font-size: 26px;
    margin-bottom: 25px;
    color: #2e7d32;
}

/* 📋 TABLE WRAPPER */
.table-wrapper {
    max-width: 900px;
    margin: auto;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(0,0,0,0.15);
}

/* 📊 TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #27ae60;
    color: #ffffff;
    padding: 15px;
    font-size: 15px;
}

td {
    padding: 14px;
    text-align: center;
    border-bottom: 1px solid #eee;
    font-size: 15px;
}

tr:hover {
    background: #f4fff7;
}

/* 🪴 IMAGE */
.order-img {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
}

/* 💰 TOTAL ROW */
.total-row td {
    font-size: 17px;
    font-weight: bold;
    background: #f6fff9;
}

/* 🔙 BACK LINK */
.back-link {
    display: inline-block;
    margin: 25px auto 0;
    padding: 12px 26px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: #ffffff;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    box-shadow: 0 10px 25px rgba(39,174,96,0.35);
    transition: all 0.35s ease;
}

.back-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(39,174,96,0.5);
}

.back-container {
    text-align: center;
}
</style>

</head>
<body>

<h2 class="page-title">📦 Order Items (Order #<?= $order_id; ?>)</h2>

<div class="table-wrapper">
<table>
<tr>
    <th>Image</th>
    <th>Plant</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
</tr>

<?php
$grand = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $grand += $row['total'];
?>
<tr>
    <td>
        <img src="<?= htmlspecialchars($row['image']); ?>" class="order-img">
    </td>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td>₹<?= number_format($row['price'], 2); ?></td>
    <td><?= $row['quantity']; ?></td>
    <td>₹<?= number_format($row['total'], 2); ?></td>
</tr>
<?php } ?>

<tr class="total-row">
    <td colspan="4" align="right">Grand Total</td>
    <td>₹<?= number_format($grand, 2); ?></td>
</tr>
</table>
</div>

<div class="back-container">
    <a href="orders.php" class="back-link">⬅ Back to Orders</a>
    <a href="index.php" class="back-link">⬅ Back to Home</a>
</div>


</body>
</html>
