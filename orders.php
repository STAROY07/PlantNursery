<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch user orders */
$query = "
SELECT 
    id,
    total_amount,
    order_date,
    status
FROM orders
WHERE user_id = $user_id
ORDER BY id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>

<style>
 .page-title {
    text-align: center;
    font-size: 30px;
    margin-bottom: 30px;

    color: rgba(241,239,224,0.5);

    background: rgba(255, 150, 255, 0.25);
    display: inline-block;
    padding: 12px 30px;
    border-radius: 12px;

    backdrop-filter: blur(10px);

    box-shadow: 0 5px 20px rgba(0,0,0,0.2);

    text-shadow: 2px 2px 10px rgba(0,0,0,0.8); /* 🔥 strong visibility */
}
/* 🌿 GLOBAL */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* 🔥 USE GLOBAL BACKGROUND */
body {
    min-height: 100vh;
    background: transparent;
    padding: 40px 20px;
}

/* 📦 PAGE TITLE */
.page-title {
    text-align: center;
    font-size: 30px;
    margin-bottom: 30px;
    color: #2e7d32;

    text-shadow: 1px 1px 8px rgba(0,0,0,0.3);
}

/* 📋 TABLE WRAPPER (GLASS EFFECT) */
.table-wrapper {
    max-width: 1000px;
    margin: auto;

    background: rgba(255,255,255,0.9);
    border-radius: 20px;
    overflow: hidden;

    box-shadow: 0 30px 80px rgba(0,0,0,0.25);
    backdrop-filter: blur(10px);
}

/* 📊 TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
th {
    background: linear-gradient(45deg, #43a047, #66bb6a);
    color: #ffffff;
    padding: 16px;
    font-size: 15px;
}

/* DATA */
td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #eee;
    font-size: 15px;
}

/* 🔥 HOVER ROW */
tr:hover {
    background: rgba(200,255,220,0.4);
}

/* 🟡 STATUS BADGE */
.status {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    color: #ffffff;
    display: inline-block;
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

/* STATUS COLORS */
.status.Pending { background: #f39c12; }
.status.OrderPlaced { background: #3498db; }
.status.Delivered { background: #27ae60; }
.status.Forwarded { background: #8e44ad; }
.status.Paid { background: #16a085; }
.status.Cancelled { background: #e74c3c; }
.status.Processing { background: #2980b9; }
.status.Shipped { background: #8e44ad; }
.status.OutforDelivery { background: #d35400; }

/* 🔗 VIEW LINK */
.view-link {
    padding: 8px 16px;
    background: linear-gradient(45deg, #43a047, #66bb6a);
    color: #ffffff;
    text-decoration: none;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;

    box-shadow: 0 8px 20px rgba(39,174,96,0.3);
    transition: all 0.3s ease;
}

/* 🔥 HOVER */
.view-link:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 15px 35px rgba(39,174,96,0.45);
}

/* 🛑 EMPTY STATE */
.empty-row td {
    padding: 30px;
    font-size: 16px;
    color: #555;
}
</style>

</head>

<?php include 'global_style.php'; ?>
<body>

<div style="text-align:center;">
    <h2 class="page-title">📦 My Orders</h2>
</div>

<div class="table-wrapper">
<table>
<tr>
    <th>Order ID</th>
    <th>Total Amount</th>
    <th>Date</th>
    <th>Status</th>
    <th>View</th>
<th>Cancel</th>
</tr>

<?php
if (mysqli_num_rows($result) == 0) {
    echo "<tr class='empty-row'><td colspan='6'>No orders found</td></tr>";
}

while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
    <td>#<?= $row['id']; ?></td>
    <td>₹<?= number_format($row['total_amount'], 2); ?></td>
    <td><?= date("d M Y", strtotime($row['order_date'])); ?></td>
    <td>
<?php
$status = $row['status'];

/* Convert status to safe CSS class */
$status_class = str_replace(' ', '', $status);
?>

<span class="status <?= $status_class; ?>">
    <?= $status; ?>
</span>

</td>
    <td>
        <a class="view-link" href="view_order_details.php?order_id=<?= $row['id']; ?>">
            View Items
        </a>
    </td>
    <td>
<?php if($row['status'] == 'Pending' || $row['status'] == 'Order Placed' || $row['status']=='Processing'|| $row['status']=='Shipped') { ?>
    <a href="cancel_order.php?id=<?= $row['id']; ?>"
       onclick="return confirm('Are you sure you want to cancel this order?')"
       style="
            padding:8px 14px;
            background:#e74c3c;
            color:#fff;
            text-decoration:none;
            border-radius:50px;
            font-size:13px;
            font-weight:600;">
       Cancel
    </a>
<?php } else { ?>
    -
<?php } ?>
</td>
</tr>
<?php } ?>


</table>
</div>
<div style="text-align:center; margin-top:25px;">
    <button onclick="goBack()" style="
        padding:10px 30px;
        font-size:15px;
        font-weight:600;
        color:#ffffff;
        background:linear-gradient(135deg,#2ecc71,#27ae60);
        border:none;
        border-radius:30px;
        cursor:pointer;
        box-shadow:0 6px 15px rgba(0,0,0,0.2);
        transition:0.2s;
    "
    onmouseover="this.style.transform='scale(1.05)'"
    onmouseout="this.style.transform='scale(1)'">
        ← Go Back
    </button>
</div>

<script>
function goBack() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
    } else {
        window.location.href = "index.php";
    }
}
</script></body>
</html>
