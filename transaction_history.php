<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

/* Fetch user orders */
$orders_query = "
SELECT id, total_amount, order_date, status, payment_method, payment_status
FROM orders 
WHERE user_id = $user_id
ORDER BY id DESC
";
$orders_result = mysqli_query($conn, $orders_query);

/* Fetch online payment gateway logs if any */
$payments_query = "
SELECT 
    p.id          AS payment_id,
    p.order_id,
    p.amount,
    p.payment_method,
    p.transaction_id,
    p.payment_date,
    p.status
FROM payments p
JOIN orders o ON p.order_id = o.id
WHERE o.user_id = $user_id
ORDER BY p.payment_date DESC
";
$payments_result = mysqli_query($conn, $payments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Transaction History | Online Plant Nursery</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

body {
  background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
  min-height: 100vh;
  color: #222;
}

/* NAV */
nav {
  background: linear-gradient(135deg, #2e7d32, #4CAF50);
  padding: 12px 20px;
  text-align: center;
  position: relative;
  box-shadow: 0 3px 12px rgba(0,0,0,0.15);
}
nav a {
  color: #fff;
  margin: 0 10px;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  padding: 6px 12px;
  border-radius: 20px;
  transition: background 0.2s;
}
nav a:hover { background: rgba(255,255,255,0.2); }

/* HEADER */
.page-header {
  text-align: center;
  padding: 35px 20px 20px;
}
.page-header h1 {
  font-size: 30px;
  color: #2e7d32;
  font-weight: 700;
}
.page-header p {
  color: #666;
  margin-top: 6px;
  font-size: 15px;
}

/* CONTAINER */
.container {
  max-width: 1050px;
  margin: 0 auto;
  padding: 20px;
}

/* STATS CARDS */
.stats-row {
  display: flex;
  gap: 16px;
  margin-bottom: 25px;
  flex-wrap: wrap;
}
.stat-card {
  flex: 1;
  min-width: 180px;
  background: #fff;
  border-radius: 14px;
  padding: 18px;
  text-align: center;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  border-left: 5px solid #4CAF50;
}
.stat-card .stat-icon { font-size: 26px; margin-bottom: 6px; }
.stat-card .stat-val {
  font-size: 24px;
  font-weight: 700;
  color: #2e7d32;
}
.stat-card .stat-label {
  font-size: 13px;
  color: #777;
  margin-top: 4px;
}

/* TABLE */
.table-wrap {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-bottom: 30px;
}
.table-title {
  background: linear-gradient(135deg, #2e7d32, #4CAF50);
  color: #fff;
  padding: 14px 20px;
  font-size: 16px;
  font-weight: 700;
}
table {
  width: 100%;
  border-collapse: collapse;
}
thead th {
  background: #f9f9f9;
  padding: 12px 14px;
  text-align: left;
  font-size: 13px;
  font-weight: 600;
  color: #555;
  border-bottom: 2px solid #eee;
}
tbody td {
  padding: 12px 14px;
  font-size: 14px;
  border-bottom: 1px solid #f5f5f5;
  color: #333;
  vertical-align: middle;
}
tbody tr:hover { background: #f9fbe7; }
tbody tr:last-child td { border-bottom: none; }

/* BADGES */
.badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}
.badge-paid    { background: #e8f5e9; color: #2e7d32; }
.badge-unpaid  { background: #fff3e0; color: #e65100; }
.badge-pending { background: #fff8e1; color: #f57f17; }
.badge-success { background: #e8f5e9; color: #1b5e20; }
.badge-failed  { background: #ffebee; color: #c62828; }

/* DOWNLOAD BUTTONS */
.btn-receipt {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: #2e7d32;
  color: #fff !important;
  padding: 6px 12px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 12.5px;
  font-weight: 600;
  transition: background 0.2s, transform 0.15s;
}
.btn-receipt:hover {
  background: #1b5e20;
  transform: translateY(-1px);
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #888;
  font-size: 15px;
}
.empty-state .empty-icon { font-size: 40px; margin-bottom: 10px; }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="index.php">🏠 Home</a>
  <a href="Flower_Plants_Shop.php">🌸 Flower Plants</a>
  <a href="Fruit_Plants_Shop.php">🍊 Fruit Plants</a>
  <a href="Medicinal_Plants_Shop.php">🌿 Medicinal Plants</a>
  <a href="air_purification_plants_detailed.php">💧 Air Purifying Plants</a>
  <a href="orders.php">📦 My Orders</a>
  <a href="profile.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-right: 5px; vertical-align: text-bottom;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> My Profile</a>
  <a href="logout.php">🚪 Logout</a>
</nav>

<div class="page-header">
  <h1>💳 Transactions & Receipts</h1>
  <p>View your order payments and download official transaction receipts</p>
</div>

<div class="container">

<?php
$total_orders  = mysqli_num_rows($orders_result);
$total_spent   = 0;
$paid_count    = 0;
while ($r = mysqli_fetch_assoc($orders_result)) {
    $total_spent += $r['total_amount'];
    if (strtolower($r['payment_status'] ?? '') === 'paid') $paid_count++;
}
?>

<div class="stats-row">
  <div class="stat-card">
    <div class="stat-icon">🛒</div>
    <div class="stat-val"><?= $total_orders ?></div>
    <div class="stat-label">Total Orders</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">✅</div>
    <div class="stat-val"><?= $paid_count ?></div>
    <div class="stat-label">Paid Orders</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">💰</div>
    <div class="stat-val">₹<?= number_format($total_spent, 2) ?></div>
    <div class="stat-label">Total Spent</div>
  </div>
</div>

<!-- Orders Table with Download Receipt Column -->
<div class="table-wrap">
  <div class="table-title">📋 Orders & Transaction Receipts</div>
  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Payment Method</th>
        <th>Payment Status</th>
        <th>Order Status</th>
        <th>Receipt</th>
      </tr>
    </thead>
    <tbody>
<?php
mysqli_data_seek($orders_result, 0);
$has_rows = false;
while ($row = mysqli_fetch_assoc($orders_result)):
    $has_rows = true;
    $ps = strtolower($row['payment_status'] ?? 'unpaid');
    $os = strtolower($row['status'] ?? 'pending');
    $ps_badge = ($ps === 'paid') ? 'badge-paid' : (($ps === 'failed') ? 'badge-failed' : 'badge-unpaid');
    $os_badge = ($os === 'delivered') ? 'badge-success' : (($os === 'cancelled') ? 'badge-failed' : 'badge-pending');
?>
      <tr>
        <td><strong>#<?= $row['id'] ?></strong></td>
        <td><?= date('d M Y, h:i A', strtotime($row['order_date'])) ?></td>
        <td><strong>₹<?= number_format($row['total_amount'], 2) ?></strong></td>
        <td><?= htmlspecialchars($row['payment_method'] ?? 'COD') ?></td>
        <td><span class="badge <?= $ps_badge ?>"><?= htmlspecialchars($row['payment_status'] ?? 'Pending') ?></span></td>
        <td><span class="badge <?= $os_badge ?>"><?= htmlspecialchars($row['status']) ?></span></td>
        <td>
          <a href="download_receipt.php?order_id=<?= $row['id'] ?>" class="btn-receipt" title="Download PDF Receipt for Order #<?= $row['id'] ?>">
            📥 Download Receipt
          </a>
        </td>
      </tr>
<?php endwhile; ?>
<?php if (!$has_rows): ?>
      <tr><td colspan="7"><div class="empty-state"><div class="empty-icon">📭</div>No transaction records found yet.</div></td></tr>
<?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Payment Records Table -->
<?php if ($payments_result && mysqli_num_rows($payments_result) > 0): ?>
<div class="table-wrap">
  <div class="table-title">💳 Gateway Transaction Logs</div>
  <table>
    <thead>
      <tr>
        <th>Payment ID</th>
        <th>Order ID</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Transaction ID</th>
        <th>Date</th>
        <th>Status</th>
        <th>Receipt</th>
      </tr>
    </thead>
    <tbody>
<?php while ($p = mysqli_fetch_assoc($payments_result)): ?>
      <tr>
        <td>#<?= $p['payment_id'] ?></td>
        <td>#<?= $p['order_id'] ?></td>
        <td><strong>₹<?= number_format($p['amount'], 2) ?></strong></td>
        <td><?= htmlspecialchars($p['payment_method']) ?></td>
        <td><code><?= htmlspecialchars($p['transaction_id'] ?? '—') ?></code></td>
        <td><?= date('d M Y', strtotime($p['payment_date'])) ?></td>
        <td>
          <span class="badge <?= strtolower($p['status']) === 'paid' || strtolower($p['status']) === 'success' ? 'badge-success' : 'badge-failed' ?>">
            <?= htmlspecialchars($p['status']) ?>
          </span>
        </td>
        <td>
          <a href="download_receipt.php?order_id=<?= $p['order_id'] ?>" class="btn-receipt">
            📥 Receipt
          </a>
        </td>
      </tr>
<?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

</div>
</body>
</html>
