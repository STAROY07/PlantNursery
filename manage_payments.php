<?php
session_start();
include 'admin_db_connection.php'; // ✅ correct admin DB

// Check admin login
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch payments
$query = "SELECT 
    p.id AS payment_id,
    IFNULL(u.name, 'Unknown') AS customer,
    p.order_id,
    p.amount,
    p.payment_method,
    p.transaction_id,
    p.payment_date,
    p.status
FROM payments p
LEFT JOIN orders o ON p.order_id = o.id
LEFT JOIN users u ON o.user_id = u.id
ORDER BY p.payment_date DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('SQL Error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Payments | Admin Panel</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
}
h2 {
    text-align: center;
    color: #2b7a2b;
}
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: #fff;
}
th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}
th {
    background: #2b7a2b;
    color: white;
}
tr:nth-child(even) {
    background: #f9f9f9;
}
.status-paid { color: green; font-weight: bold; }
.status-cod { color: orange; font-weight: bold; }
.status-failed { color: red; font-weight: bold; }
.status-pending { color: gray; font-weight: bold; }

.back {
    margin-top:20px;
    text-align:center;
}
.back a {
    text-decoration:none;
    color:green;
    font-weight:bold;
}
</style>
</head>

<body>

<h2>Manage Payments</h2>

<?php if (mysqli_num_rows($result) == 0) { ?>

<p style="text-align:center;color:red;">No payments found!</p>

<?php } else { ?>

<table>
<tr>
    <th>Payment ID</th>
    <th>Customer</th>
    <th>Order ID</th>
    <th>Amount</th>
    <th>Method</th>
    <th>Transaction ID</th>
    <th>Payment Date</th>
    <th>Status</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['payment_id']; ?></td>
    <td><?= htmlspecialchars($row['customer']); ?></td>
    <td><?= $row['order_id']; ?></td>
    <td>₹<?= $row['amount']; ?></td>
    <td><?= $row['payment_method']; ?></td>
    <td>
<?php 
if($row['payment_method'] == 'COD'){
    echo "COD";
} else {
    echo $row['transaction_id'] ?: '-';
}
?>
</td>
    <td><?= $row['payment_date']; ?></td>

    <td class="status-<?= strtolower($row['status']); ?>">
        <?= ucfirst($row['status']); ?>
    </td>
</tr>
<?php } ?>

</table>

<?php } ?>

<div class="back">
    <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
</div>

</body>
</html>
