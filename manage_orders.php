<?php
session_start();
include 'admin_db_connection.php';

/* 🔒 CHECK ADMIN LOGIN */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* 🔄 UPDATE ORDER STATUS (MANUAL DROPDOWN LOGIC) */
/* 🔄 AUTO NEXT STEP UPDATE */

    // If cancelled → refund
    if (isset($_POST['update_status'])) {

        $order_id = (int)$_POST['order_id'];
        $new_status = trim($_POST['status']);

    
        // Get payment method
        $pay_q = mysqli_query($conn,
            "SELECT payment_method FROM orders WHERE id='$order_id'"
        );
        $pay_data = mysqli_fetch_assoc($pay_q);
        $payment_method = strtolower(trim($pay_data['payment_method']));
    
        // Default: no payment status change
        $payment_status = null;
    
        if ($new_status == "Cancelled") {

            if ($payment_method == "razorpay") {
        
                // Razorpay → Refunded
                mysqli_query($conn,
                    "UPDATE payments 
                     SET status='Refunded' 
                     WHERE order_id='$order_id'"
                );
        
                mysqli_query($conn,
                    "UPDATE orders 
                     SET status='Cancelled',
                         payment_status='Refunded'
                     WHERE id='$order_id'"
                );
        
            } else {
        
                // COD → Cancelled
                mysqli_query($conn,
                    "UPDATE payments 
                     SET status='Cancelled' 
                     WHERE order_id='$order_id'"
                );
        
                mysqli_query($conn,
                    "UPDATE orders 
                     SET status='Cancelled',
                         payment_status='Cancelled'
                     WHERE id='$order_id'"
                );
            }
        
            header("Location: manage_orders.php");
            exit();
        }
    
    
        
        // ===== DELIVERED COD LOGIC =====
        if ($new_status == "Delivered" && $payment_method == "cod") {

            mysqli_query($conn,
                "UPDATE payments 
                 SET status='Paid' 
                 WHERE order_id='$order_id'"
            );
        
            mysqli_query($conn,
                "UPDATE orders 
                 SET status='Delivered',
                     payment_status='Paid'
                 WHERE id='$order_id'"
            );
        
            header("Location: manage_orders.php");
            exit();
        }
    
        // ===== OTHER STATUS =====
     // ===== OTHER STATUS =====

// 🔹 Get current payment status
$current_q = mysqli_query($conn,
"SELECT payment_status FROM orders WHERE id='$order_id'"
);
$current_data = mysqli_fetch_assoc($current_q);
$current_payment_status = $current_data['payment_status'];

// 🔹 Update without changing payment status
mysqli_query($conn,
"UPDATE orders 
 SET status='$new_status',
     payment_status='$current_payment_status'
 WHERE id='$order_id'"
);

header("Location: manage_orders.php");
exit();
    }

/* 📦 FETCH ALL ORDERS */
$query = "
SELECT 
    id AS order_id,
    user_id,
    total_amount,
    order_date,
    status,
    payment_method,
    payment_status
FROM orders
ORDER BY order_date DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Orders | Admin Panel</title>

<style>
body { font-family: Arial, sans-serif; background:#f9f9f9; }
h2 { color:#2b7a2b; text-align:center; }

table {
    width:90%;
    border-collapse:collapse;
    margin:20px auto;
    background:white;
}

th, td {
    padding:10px;
    border:1px solid #ccc;
    text-align:center;
}

th {
    background:#2b7a2b;
    color:white;
}

select { padding:5px; }

button {
    padding:5px 10px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
}

button:hover { background:#218838; }

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

<h2>Manage Orders</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>User ID</th>
    <th>Date</th>
    <th>Status</th>
    <th>Total Amount</th>
    <th>Payment Method</th>
    <th>Payment Status</th>
    <th>Update</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<form method="post">

    <td><?= $row['order_id'] ?></td>
    <td><?= $row['user_id'] ?></td>
    <td><?= $row['order_date'] ?></td>

    <!-- STATUS DROPDOWN -->
    <td>
        <select name="status">
            <?php
            $statuses = [
                "Order Placed",
                "Processing",
                "Shipped",
                "Out for Delivery",
                "Delivered",
                "Cancelled"  
            ];

            foreach ($statuses as $s) {
                $selected = ($row['status'] == $s) ? "selected" : "";
                echo "<option value='$s' $selected>$s</option>";
            }
            ?>
        </select>
    </td>

    <td>₹<?= $row['total_amount'] ?></td>

    <td><?= $row['payment_method'] ?></td>

    <td>
    <?php
$status = strtolower($row['payment_status']);

if ($status == 'paid') {
    echo "<span style='color:green;font-weight:bold'>Paid</span>";
} elseif ($status == 'refunded') {
    echo "<span style='color:blue;font-weight:bold'>Refunded</span>";
} elseif ($status == 'cancelled') {
    echo "<span style='color:red;font-weight:bold'>Cancelled</span>";
} else {
    echo "<span style='color:gray'>Pending</span>";
}
?>
</td>

    <td>
        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
        <button type="submit" name="update_status">Update</button>
    </td>

</form>
</tr>
<?php } ?>

</table>

<div class="back">
    <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
</div>

</body>
</html>