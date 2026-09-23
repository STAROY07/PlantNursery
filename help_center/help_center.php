<!DOCTYPE html>
<html>
<head>
    <title>Help Center | Online Plant Nursery</title>
    <link rel="stylesheet" href="../css/help_center.css">

<style>
body{
    font-family: Arial;
    background:#e8f5e9;
}

/* NAVBAR STYLE */
nav{
    background:#2e7d32;
    text-align:center;
    padding:10px 0;
}

nav a {
    color: white;
    text-decoration: none;
    padding: 12px 18px;
    display: inline-block;
    transition: 0.3s;
    font-weight:bold;
}

nav a:hover {
    background-color: #1b5e20;
    border-radius:5px;
}

/* HELP BOX */
.help-container{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:25px;
    padding:40px;
    max-width:1200px;
    margin:auto;
}

.help-box{
    background:white;
    border:2px solid #2e7d32;
    padding:20px;
    text-align:center;
    text-decoration:none;
    color:#2e7d32;
    font-weight:bold;
    border-radius:8px;
}

.help-box:hover{
    background:#2e7d32;
    color:white;
}
</style>

</head>

<?php include 'global_style.php'; ?>
<body>

<h1 style="text-align:center;color:#2e7d32;">🌿 Help Center</h1>

<nav>
  <a href="../index.php">🏠 Home</a>
 
  <a href="../about.html">ℹ️ About</a>
  <a href="../contact.php">💬 FeedBack</a>
  <a href="../cart.php">🛒 Cart</a>
  
  <a href="../profile.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-right: 5px; vertical-align: text-bottom;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> My Profile</a>
  <a href="../orders.php">📦 My Orders</a>
  <a href="../track_order.php">🚚 Track Order</a>
</nav>

<p style="text-align:center;">We are here to help you</p>

<div class="help-container">

    <a href="faq.php" class="help-box">FAQs</a>
    <a href="contact_support.php" class="help-box">Contact Support</a>
    <a href="order_help.php" class="help-box">Order & Payment Help</a>
    <a href="delivery_returns.php" class="help-box">Delivery & Returns</a>
    <a href="../plant_diseases_100.php" class="help-box">Plant Care Guide</a>
    <a href="account_help.php" class="help-box">Account Help</a>
    <a href="privacy_policy.php" class="help-box">Privacy Policy</a>
    <a href="terms_conditions.php" class="help-box">Terms & Conditions</a>

</div>

</body>
</html>