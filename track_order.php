<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* status helper function */
function showStatus($current, $step) {
    $steps = ["Order Placed","Processing","Shipped","Out for Delivery","Delivered"];
    return array_search($current, $steps) >= array_search($step, $steps)
        ? "done" : "pending";
}

/* 🔥 CHECK IF FORWARD BUTTON SENT ID */
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $sql = "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'";
} else {
    $sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Order</title>
    <style>
body {
    font-family: Arial;
    background: transparent; /* 🔥 use global background */
}

/* 🔥 MAIN CONTAINER */
.container {
    width: 70%;
    margin: 30px auto;

    background: rgba(255,255,255,0.9);
    padding: 25px;
    border-radius: 14px;

    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    backdrop-filter: blur(10px);
}

/* 🔥 ORDER BOX */
.order-box {
    border: none;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 10px;

    background: rgba(255,255,255,0.95);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);

    transition: 0.3s;
}

/* HOVER EFFECT */
.order-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
}

/* 🔥 STATUS STYLES */
.done {
    color: #2e7d32;
    font-weight: bold;
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(76,175,80,0.2);
}

.pending {
    color: #f39c12;
    font-weight: bold;
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(255,165,0,0.2);
}
</style>
</head>
<?php include 'global_style.php'; ?>
<body>
<div class="container">
    <h2 align="center">Track Your Orders</h2>

<?php
$sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $order_id = $row['id'];
        $status   = $row['status'];
?>

<div class="order-box">
    <p><strong>Order ID:</strong> <?= $order_id ?></p>
    <p><strong>Order Date:</strong> <?= $row['order_date'] ?></p>

    <?php if ($status == "Cancelled") { ?>

<div style="color:red; font-weight:bold;">
    ❌ This Order Has Been Cancelled
</div>

<?php } else { ?>

<div class="<?= showStatus($status,'Order Placed') ?>">✔ Order Placed</div>
<div class="<?= showStatus($status,'Processing') ?>">✔ Processing</div>
<div class="<?= showStatus($status,'Shipped') ?>">✔ Shipped</div>
<div class="<?= showStatus($status,'Out for Delivery') ?>">✔ Out for Delivery</div>
<div class="<?= showStatus($status,'Delivered') ?>">✔ Delivered</div>

<?php } ?>
</div>


<?php
    }
} else {
    echo "<p>No orders found.</p>";
}
?>

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
</script>
</body>
</html>
