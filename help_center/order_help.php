<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order & Payment Help</title>

<style>
body{
    font-family: "Segoe UI", Arial, sans-serif;
    background:#eef6ee;
    margin:0;
    padding:0;
}

.order-wrapper{
    max-width:900px;
    margin:40px auto;
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.order-wrapper h2{
    text-align:center;
    color:#2e7d32;
    margin-bottom:25px;
}

/* Section Card */
.section{
    background:#f9fdf9;
    border-left:5px solid #2e7d32;
    padding:20px;
    border-radius:8px;
    margin-bottom:25px;
}

.section h3{
    margin-top:0;
    color:#2e7d32;
}

/* Lists */
ol, ul{
    padding-left:20px;
}

ol li, ul li{
    margin-bottom:8px;
    color:#333;
}

/* Payment Box */
.payment-box{
    background:#f3f8ff;
    border-left:5px solid #1976d2;
    padding:20px;
    border-radius:8px;
}

.payment-box h3{
    color:#1976d2;
}

/* Back link */
.back-link{
    display:block;
    text-align:center;
    margin-top:30px;
    text-decoration:none;
    color:#2e7d32;
    font-weight:600;
}

.back-link:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="order-wrapper">

    <h2>🛒 Order & Payment Help</h2>

    <!-- Order Steps -->
    <div class="section">
        <h3>How to Place an Order</h3>
        <ol>
            <li>Browse plants by category or search.</li>
            <li>Select a plant to view details and price.</li>
            <li>Click on <b>Add to Cart</b>.</li>
            <li>Open Cart and verify selected items.</li>
            <li>Click <b>Proceed to Checkout</b>.</li>
            <li>Choose a payment method and confirm order.</li>
        </ol>
    </div>

    <!-- Payment Methods -->
    <div class="payment-box">
        <h3>Available Payment Methods</h3>
        <ul>
            <li>UPI (Google Pay, PhonePe, Paytm)</li>
            <li>Debit Card</li>
            <li>Credit Card</li>
            <li>Cash on Delivery (COD)</li>
        </ul>
    </div>

    <!-- Order Confirmation -->
    <div class="section">
        <h3>Order Confirmation</h3>
        <ul>
            <li>You will receive an order confirmation message after successful payment.</li>
            <li>Order details can be viewed in the <b>My Orders</b> section.</li>
            <li>Email confirmation will be sent to your registered email ID.</li>
        </ul>
    </div>

    <!-- Payment Issues -->
    <div class="section">
        <h3>Payment Issues & Solutions</h3>
        <ul>
            <li>If payment fails, do not worry. You can try again.</li>
            <li>Amount deducted will be refunded within 3–5 working days.</li>
            <li>For any payment issue, contact our support team.</li>
        </ul>
    </div>

    <!-- Order Cancellation -->
    <div class="section">
        <h3>Order Cancellation</h3>
        <ul>
            <li>Orders can be cancelled before shipment.</li>
            <li>Once shipped, cancellation is not allowed.</li>
            <li>Refund will be processed to original payment method.</li>
        </ul>
    </div>

    <a href="help_center.php" class="back-link">← Back to Help Center</a>

</div>

</body>
</html>
