<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* Update status */
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status   = $_POST['status'];

    mysqli_query($conn,
        "UPDATE orders SET status='$status' WHERE id='$order_id'"
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; }
        table { width:90%; margin:30px auto; border-collapse: collapse; background:#fff; }
        th, td { padding:10px; border:1px solid #ccc; text-align:center; }
        th { background:#2e7d32; color:white; }
        select, button { padding:5px; }
        button { background:green; color:white; border:none; cursor:pointer; }
    </style>
</head>

<body>

<h2 align="center">Manage Orders</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>User ID</th>
    <th>Total Amount</th>
    <th>Order Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['user_id'] ?></td>
    <td>₹<?= $row['total_amount'] ?></td>
    <td><?= $row['order_date'] ?></td>

    <form method="post">
        <td>
            <select name="status">
                <?php
                $statuses = [
                    "Order Placed",
                    "Processing",
                    "Shipped",
                    "Out for Delivery",
                    "Delivered"
                ];
                foreach ($statuses as $s) {
                    $selected = ($row['status'] == $s) ? "selected" : "";
                    echo "<option $selected>$s</option>";
                }
                ?>
            </select>
        </td>
        <td>
            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
            <button name="update_status">Update</button>
        </td>
    </form>
</tr>
<?php } ?>

</table>

</body>
</html>
