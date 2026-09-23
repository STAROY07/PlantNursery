<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* ✅ Get last order id set during confirm order */
$order_id = $_SESSION['last_order_id'] ?? null;

/* ✅ Clear payment flag to avoid refresh issues */
unset($_SESSION['payment_pending']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Success</title>

<style>
/* 🌿 GLOBAL */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* 🔥 IMPORTANT: use global background */
body {
    min-height: 100vh;
    background: transparent;

    display: flex;
    align-items: center;
    justify-content: center;
}

/* ✅ SUCCESS CARD (GLASS EFFECT) */
.success-box {
    background: rgba(255,255,255,0.9);
    width: 420px;
    padding: 35px;
    border-radius: 22px;
    text-align: center;

    box-shadow: 0 30px 80px rgba(0,0,0,0.25);
    backdrop-filter: blur(10px);

    animation: popIn 0.6s ease;
}

/* 🔥 ANIMATION */
@keyframes popIn {
    from { transform: scale(0.9); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}

/* 🎉 ICON */
.success-icon {
    font-size: 65px;
    margin-bottom: 15px;
}

/* 🟢 TITLE */
.success-box h2 {
    color: #2e7d32;
    margin-bottom: 12px;
}

/* 📝 TEXT */
.success-box p {
    color: #555;
    font-size: 15px;
    margin-bottom: 12px;
    line-height: 1.6;
}

/* 📄 INFO SECTION */
.info {
    background: rgba(240,255,245,0.9);
    border-radius: 15px;
    padding: 15px;
    margin: 18px 0;
    font-size: 15px;
    color: #333;

    box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
}

/* 📧 EMAIL STATUS */
.info p {
    margin-bottom: 8px;
}

/* 🔗 BUTTON LINKS */
.success-links {
    margin-top: 20px;
}

/* 🔥 BUTTON STYLE */
.success-links a {
    display: inline-block;
    margin: 6px;
    padding: 12px 22px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    color: #ffffff;

    background: linear-gradient(45deg, #43a047, #66bb6a);
    box-shadow: 0 10px 25px rgba(39,174,96,0.35);

    transition: all 0.35s ease;
}

/* 🔥 HOVER EFFECT */
.success-links a:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 18px 40px rgba(39,174,96,0.5);
}
</style>

</head>

<?php include 'global_style.php'; ?>
<body>

<div class="success-box">
    <div class="success-icon">🎉</div>
    <h2>Order Placed Successfully!</h2>

    <p>
        Thank you for your order 🌱<br>
        Your plants will be delivered soon.
    </p>

    <div class="info">
        <p>💵 <strong>Payment Method:</strong> Cash on Delivery</p>

        <?php if ($order_id): ?>
            <p>🧾 <strong>Order ID:</strong> #<?php echo $order_id; ?></p>
        <?php endif; ?>

        <!-- 📧 Email Status -->
        <?php if (isset($_SESSION['mail_status'])): ?>
            <?php if ($_SESSION['mail_status'] === 'sent'): ?>
                <p style="color:#27ae60;">📧 Receipt email sent successfully.</p>
            <?php else: ?>
                <p style="color:#e74c3c;">
                    ❌ Email failed<br>
                    <small><?php echo $_SESSION['mail_status']; ?></small>
                </p>
            <?php endif; ?>
            <?php unset($_SESSION['mail_status']); ?>
        <?php else: ?>
            <p style="color:#555;">📧 Receipt processing completed.</p>
        <?php endif; ?>
    </div>

    <div class="success-links">
        <a href="index.php">🏠 Home</a>
        <a href="orders.php">📦 View Orders</a>
    </div>
</div>

</body>
</html>
