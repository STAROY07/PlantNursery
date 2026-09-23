<?php
session_start();
include 'admin_db_connection.php';

/* 🔒 CHECK ADMIN LOGIN */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = htmlspecialchars($_SESSION['admin']);

/* 📊 DASHBOARD STATISTICS */

// Total Users
$user_q = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$user_data = mysqli_fetch_assoc($user_q);
$total_users = $user_data['total_users'] ?? 0;

// Total Orders
$order_q = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM orders");
$order_data = mysqli_fetch_assoc($order_q);
$total_orders = $order_data['total_orders'] ?? 0;

// Pending Orders
$pending_q = mysqli_query($conn, "SELECT COUNT(*) AS pending_orders FROM orders WHERE status='Pending'");
$pending_data = mysqli_fetch_assoc($pending_q);
$pending_orders = $pending_data['pending_orders'] ?? 0;

// Total Revenue
$revenue_q = mysqli_query($conn, "SELECT SUM(total_amount) AS total_revenue FROM orders WHERE status IN ('Order Placed','Delivered')");
$revenue_data = mysqli_fetch_assoc($revenue_q);
$total_revenue = $revenue_data['total_revenue'] ?? 0;

// Total Plants
$plant_q = mysqli_query($conn, "SELECT COUNT(*) AS total_plants FROM plants");
$plant_data = mysqli_fetch_assoc($plant_q);
$total_plants = $plant_data['total_plants'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard | Online Plant Nursery</title>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #e6f4ea, #f0f9f0);
    margin: 0;
}

/* ===== HEADER ===== */
header {
    background: linear-gradient(135deg, #1b5e20, #2e7d32);
    color: white;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

header h1 {
    margin: 0;
    font-size: 26px;
    letter-spacing: 1px;
}

header p {
    margin-top: 8px;
    font-size: 14px;
}

/* ===== STATISTICS SECTION ===== */
.stats {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 25px;
    padding: 40px 20px;
}

.stat-box {
    background: white;
    width: 220px;
    padding: 25px;
    text-align: center;
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.stat-box h3 {
    margin: 0;
    font-size: 16px;
    color: #666;
    margin-bottom: 10px;
}

.stat-box p {
    font-size: 26px;
    font-weight: bold;
    color: #1b5e20;
}

/* ===== MANAGEMENT CARDS ===== */
.container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 25px;
    padding: 20px 20px 60px;
}

.card {
    background: white;
    width: 240px;
    padding: 30px 20px;
    text-align: center;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 30px 70px rgba(0,0,0,0.15);
}

.card h2 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #333;
}

/* BUTTON STYLE */
.card a {
    display: inline-block;
    margin-top: 15px;
    padding: 12px 22px;
    font-size: 14px;
    font-weight: 600;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    box-shadow: 0 10px 30px rgba(39,174,96,0.35);
    transition: all 0.3s ease;
}

.card a:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(39,174,96,0.5);
}


/* ===== FOOTER ===== */
footer {
    background: linear-gradient(135deg, #1b5e20, #2e7d32);
    color: white;
    text-align: center;
    padding: 15px;
    font-size: 14px;
}


</style>
</head>

<body>

<header>
    <h1>🌱 Online Plant Nursery - Admin Dashboard</h1>
    <p>Welcome, <?= $admin_name; ?> | 
    <a href="admin_logout.php" style="color:#ffd;">Logout</a></p>
</header>

<!-- 📊 STATISTICS SECTION -->
<div class="stats">

    <div class="stat-box">
        <h3>Total Users</h3>
        <p><?= $total_users ?></p>
    </div>

    <div class="stat-box">
        <h3>Total Orders</h3>
        <p><?= $total_orders ?></p>
    </div>

    <div class="stat-box">
        <h3>Pending Orders</h3>
        <p><?= $pending_orders ?></p>
    </div>

    <div class="stat-box">
        <h3>Total Revenue</h3>
        <p>₹<?= number_format($total_revenue,2) ?></p>
    </div>

    <div class="stat-box">
        <h3>Total Plants</h3>
        <p><?= $total_plants ?></p>
    </div>

</div>

<!-- 🔧 MANAGEMENT CARDS -->
<div class="container">

    <div class="card">
        <h2>📦 Manage Orders</h2>
        <a href="manage_orders.php">Go</a>
    </div>

    <div class="card">
    <h2>🌱 Manage Plants</h2>
    <a href="admin_manage_plants.php">Go</a>
</div>

    <div class="card">
        <h2>👥 Manage Users</h2>
        <a href="manage_users.php">Go</a>
    </div>

    <div class="card">
        <h2>💳 Manage Payments</h2>
        <a href="manage_payments.php">Go</a>
    </div>

    <div class="card">
        <h2>📝 Feedbacks</h2>
        <a href="view_message.php">Go</a>
    </div>

    <div class="card">
        <h2>📨 Contact Messages</h2>
        <a href="manage_contact_support.php">Go</a>
    </div>

</div>

<footer>
    © <?= date("Y"); ?> Online Plant Nursery | Admin Panel
</footer>

</body>
</html>
