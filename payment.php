<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['payment_pending']) || !isset($_SESSION['order_id'])) {
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* 🔥 IMPORTANT: allow global background */
body{
    min-height:100vh;
    background:transparent;

    display:flex;
    align-items:center;
    justify-content:center;
}

/* 🔥 GLASS CARD */
.payment-card{
    background:rgba(255,255,255,0.9);
    width:400px;
    padding:40px;
    border-radius:22px;
    text-align:center;

    box-shadow:0 30px 80px rgba(0,0,0,0.25);
    backdrop-filter:blur(10px);

    animation:fadeIn 1s ease-in-out;
}

/* ICON */
.payment-icon{
    font-size:60px;
    margin-bottom:15px;
}

/* TITLE */
.payment-card h2{
    color:#2e7d32;
    margin-bottom:25px;
}

/* OPTIONS */
.payment-option{
    display:flex;
    align-items:center;
    margin-bottom:18px;
    font-size:16px;
    justify-content:flex-start;
}

/* RADIO */
.payment-option input{
    margin-right:10px;
    transform:scale(1.2);
    cursor:pointer;
}

/* BUTTON GROUP */
.btn-group{
    margin-top:30px;
    display:flex;
    justify-content:center;
    gap:15px;
}

/* 🔥 CONFIRM BUTTON */
.confirm-btn{
    padding:14px 30px;
    font-weight:600;
    color:#fff;

    background:linear-gradient(45deg,#43a047,#66bb6a);
    border:none;
    border-radius:50px;
    cursor:pointer;

    transition:0.3s;
}

.confirm-btn:hover{
    transform:scale(1.05);
}

/* 🔥 CANCEL BUTTON */
.cancel-btn{
    padding:14px 30px;
    background:linear-gradient(45deg,#e74c3c,#c0392b);
    color:#fff;
    text-decoration:none;
    border-radius:50px;

    transition:0.3s;
}

.cancel-btn:hover{
    transform:scale(1.05);
}

/* 🔥 ANIMATION */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
</head>
<?php include 'global_style.php'; ?>
<body>

<div class="payment-card">
    <div class="payment-icon">💳</div>
    <h2>Select Payment Method</h2>

    <div class="payment-option">
        <input type="radio" name="payment_method" value="COD" id="cod" checked>
        <label for="cod">Cash on Delivery</label>
    </div>

    <div class="payment-option">
        <input type="radio" name="payment_method" value="RAZORPAY" id="razorpay">
        <label for="razorpay">Online Payment (UPI / Card / Wallet)</label>
    </div>

    <div class="btn-group">
        <button type="button" class="confirm-btn" onclick="processPayment()">
            Confirm Order
        </button>

        <a href="cart.php" class="cancel-btn">Cancel</a>
    </div>
</div>

<!-- Razorpay JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
function processPayment() {

    let method = document.querySelector('input[name="payment_method"]:checked').value;

    /* ================= COD ================= */
    if (method === "COD") {
        window.location.href = "payment_success.php?method=COD";
        return;
    }

    /* ============== RAZORPAY ============== */

    fetch("create_razorpay_order.php")
.then(res => res.json())
.then(data => {

    if (data.error) {
        alert(data.error);
        return;
    }

    var options = {
        "key": data.key,
        "amount": data.amount,
        "currency": "INR",
        "name": "Online Plant Nursery",
        "description": "Plant Purchase",
        "order_id": data.order_id,
        "config": {
            "display": {
                "blocks": {
                    "utib": {
                        "name": "Pay via UPI ID / QR",
                        "instruments": [
                            { 
                                "method": "upi",
                                "flows": ["collect", "qr"]
                            }
                        ]
                    }
                },
                "sequence": ["block.utib"],
                "preferences": {
                    "show_default_blocks": true
                }
            }
        },
        "handler": function (response) {
            window.location.href =
                "verify_payment.php" +
                "?razorpay_payment_id=" + response.razorpay_payment_id +
                "&razorpay_order_id=" + response.razorpay_order_id +
                "&razorpay_signature=" + response.razorpay_signature;
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
})
.catch(err => {
    alert("Payment initialization failed.");
});

}
</script>

</body>
</html>
