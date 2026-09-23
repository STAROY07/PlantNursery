<?php
session_start();

include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Cart</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* 🔥 IMPORTANT: allow global background */
body {
    background: transparent;
    min-height: 100vh;
    padding: 40px 20px;
}

/* 🔥 GLASS CONTAINER */
.cart-container {
    max-width: 1000px;
    margin: auto;
    background: rgba(255,255,255,0.9);
    border-radius: 22px;
    padding: 30px;

    box-shadow: 0 30px 80px rgba(0,0,0,0.25);
    backdrop-filter: blur(10px);
}

/* TITLE */
.cart-title {
    text-align: center;
    margin-bottom: 25px;
    color: #2e7d32;
    font-size: 28px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
th {
    background: linear-gradient(45deg, #43a047, #66bb6a);
    color: #ffffff;
    padding: 14px;
    text-align: center;
    border-radius: 6px;
}

/* DATA */
td {
    padding: 14px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

/* IMAGE */
.cart-img {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
    transition: 0.3s;
}

/* 🔥 IMAGE HOVER */
.cart-img:hover {
    transform: scale(1.1);
}

/* QUANTITY BUTTON */
.qty-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    background: #2e7d32;
    color: #ffffff;
    transition: 0.3s;
}

.qty-btn:hover {
    transform: scale(1.1);
}

/* VALUE */
.qty-value {
    margin: 0 10px;
    font-weight: bold;
}

/* REMOVE BUTTON */
.remove-btn {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: red;
    transition: 0.3s;
}

.remove-btn:hover {
    transform: scale(1.2);
}

/* TOTAL */
.total-row td {
    font-size: 18px;
    font-weight: bold;
    color: #2e7d32;
}

/* 🔥 PLACE ORDER BUTTON */
.place-order {
    margin-top: 25px;
    text-align: center;
}

.place-order button {
    padding: 14px 35px;
    font-size: 17px;
    font-weight: 600;
    color: #ffffff;

    background: linear-gradient(45deg, #43a047, #66bb6a);
    border: none;
    border-radius: 50px;
    cursor: pointer;

    transition: 0.3s;
}

.place-order button:hover {
    transform: scale(1.05);
}

/* EMPTY CART */
.empty-cart {
    text-align: center;
    font-size: 18px;
    color: #555;
    padding: 30px;
}
</style>
</head>
<?php include 'global_style.php'; ?>
<body>


<div class="cart-container">
<?php
if(isset($_SESSION['error'])){
    echo "<div style='
        background:#ffdddd;
        color:#c0392b;
        padding:12px;
        border-radius:8px;
        margin-bottom:15px;
        text-align:center;
        font-weight:600;
    '>
        ".$_SESSION['error']."
    </div>";
    unset($_SESSION['error']);
}
?>
    <h2 class="cart-title">🛒 My Cart</h2>

    <table>
        <tr>
            <th>Image</th>
            <th>Plant</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>

        <?php
        $grand = 0;

        if (mysqli_num_rows($result) == 0) {
            echo "<tr><td colspan='6' class='empty-cart'>Your cart is empty</td></tr>";
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $total = $row['price'] * $row['quantity'];
            $grand += $total;
        ?>
        <tr>
            <td>
                <img src="<?= htmlspecialchars($row['image']); ?>" class="cart-img">
            </td>

            <td><?= htmlspecialchars($row['plant_name']); ?></td>

            <td>₹<?= $row['price']; ?></td>

            <td>
                <form action="update_cart.php" method="post" style="display:inline;">
                    <input type="hidden" name="cart_id" value="<?= $row['id']; ?>">
                    <input type="hidden" name="action" value="decrease">
                    <button type="submit" class="qty-btn">−</button>
                </form>

                <span class="qty-value"><?= $row['quantity']; ?></span>

                <form action="update_cart.php" method="post" style="display:inline;">
                    <input type="hidden" name="cart_id" value="<?= $row['id']; ?>">
                    <input type="hidden" name="action" value="increase">
                    <button type="submit" class="qty-btn">+</button>
                </form>
            </td>

            <td>₹<?= $total; ?></td>

            <td>
                <form action="remove_cart.php" method="post">
                    <input type="hidden" name="cart_id" value="<?= $row['id']; ?>">
                    <button type="submit" class="remove-btn">❌</button>
                </form>
            </td>
        </tr>
        <?php } ?>

        <?php if ($grand > 0): ?>
        <tr class="total-row">
            <td colspan="4" align="right">Grand Total</td>
            <td colspan="2">₹<?= $grand; ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <?php if ($grand > 0): ?>
    <div class="place-order">
        <form action="place_order.php" method="post">
            <input type="hidden" name="confirm_order" value="1">
            <button type="submit">Place Order</button>
            <button type="button" onclick="goBack()">Go Back</button>

<script>
function goBack() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
    } else {
        window.location.href = "index.php"; // fallback
    }
}
</script>
        </form>
    </div>
    <?php endif; ?>

</div>

</body>
</html>
