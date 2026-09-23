<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Online Plant Nursery</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  color: #333;
}

/* 🔥 HEADER IMPROVED */
header {
  padding: 20px 0;
  text-align: center;
  color: white;
  background: rgba(0, 80, 0, 0.7);
  backdrop-filter: blur(10px);
}

header h1 {
  margin: 0;
  font-size: 2rem;
  text-shadow: 0 0 10px rgba(255,255,255,0.6);
}

/* 🔥 NAVBAR IMPROVED */
nav {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;

  background: rgba(0, 100, 0, 0.6);
  backdrop-filter: blur(10px);
}

nav a {
  color: white;
  text-decoration: none;
  padding: 15px 20px;
  display: inline-block;
  transition: 0.3s;
  font-weight: 500;
}

nav a:hover {
  background: rgba(255,255,255,0.2);
  border-radius: 5px;
}

/* LOGIN */
.login-menu {
  position: absolute;
  right: 20px;
}

.login-btn {
  background: none;
  border: none;
  cursor: pointer;
}

.dropdown {
  display: none;
  position: absolute;
  right: 0;
  background: white;
  border-radius: 6px;
  box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
  min-width: 120px;
  z-index: 10;
}

.dropdown a {
  display: block;
  padding: 10px;
  text-decoration: none;
  color: #333;
}

.dropdown a:hover {
  background: #e8f5e9;
  color: #2e7d32;
}

.login-menu:hover .dropdown {
  display: block;
}

/* 🔥 HERO IMPROVED */
.hero {
  height: 350px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  color: white;
}

.hero h2 {
  font-size: 2.8rem;
  font-weight: bold;

  background: rgba(0, 100, 0, 0.6);
  padding: 20px 40px;
  border-radius: 12px;

  backdrop-filter: blur(8px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.3);

  animation: fadeIn 1.5s ease-in-out;
}

/* 🔥 SECTION TITLE FIX (visibility on bg) */
.section {
  padding: 40px 20px;
  text-align: center;
}

.section h3 {
  margin-bottom: 20px;
  font-size: 2rem;
  color: white;
  text-shadow: 2px 2px 10px rgba(0,0,0,0.6);
}

/* 🔥 BUTTONS IMPROVED */
.buttons {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 15px;
}

.buttons a {
  background: linear-gradient(45deg, #43a047, #66bb6a);
  color: white;
  padding: 15px 25px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;

  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  transition: all 0.3s ease;
}

.buttons a:hover {
  transform: translateY(-5px) scale(1.05);
  background: linear-gradient(45deg, #2e7d32, #4caf50);
}

/* FOOTER */
footer {
  text-align: center;
  padding: 20px;
  background-color: rgba(200, 230, 201, 0.8);
  font-size: 0.9rem;
  color: #333;
}

/* MOBILE */
@media (max-width: 768px) {
  .hero h2 {
    font-size: 1.8rem;
    padding: 15px;
  }

  .buttons a {
    width: 80%;
    padding: 12px;
  }
}

/* 🔥 ANIMATION */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
  </style>
</head>
<?php include 'global_style.php'; ?>
<body>

<header>
  <h1><i class="fa-solid fa-leaf" style="color: #a5d6a7; margin-right: 8px;"></i> Online Plant Nursery</h1>
</header>

<nav>
  <a href="index.php"><i class="fa-solid fa-house" style="margin-right: 6px;"></i> Home</a>
  <a href="plant_diseases_100.php"><i class="fa-solid fa-shield-virus" style="margin-right: 6px;"></i> Diseases</a>
  <a href="about.html"><i class="fa-solid fa-circle-info" style="margin-right: 6px;"></i> About</a>
  <a href="contact.php"><i class="fa-solid fa-comments" style="margin-right: 6px;"></i> Feedback</a>
  <a href="cart.php"><i class="fa-solid fa-cart-shopping" style="margin-right: 6px;"></i> Cart</a>
  <a href="help_center/help_center.php"><i class="fa-solid fa-circle-question" style="margin-right: 6px;"></i> Help Center</a>
  <a href="profile.php"><i class="fa-solid fa-user" style="margin-right: 6px;"></i> My Profile</a>
  <a href="orders.php"><i class="fa-solid fa-box" style="margin-right: 6px;"></i> My Orders</a>
  <a href="track_order.php"><i class="fa-solid fa-truck" style="margin-right: 6px;"></i> Track Order</a>

  <div class="login-menu">
    <button class="login-btn">
      <img src="Images/login_icon.jpeg" width="40" alt="Login">
    </button>
    <div class="dropdown">
      <a href="transaction_history.php"><i class="fa-solid fa-receipt" style="margin-right: 6px;"></i> Transactions</a>
      <a href="logout.php"><i class="fa-solid fa-right-from-bracket" style="margin-right: 6px;"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="hero">
  <h2>Welcome to Green Living</h2>
</div>

<div class="section">
  <h3>Explore Our Plant Categories</h3>
  <div class="buttons">
    <a href="Flower_Plants_Shop.php"><i class="fa-solid fa-spa" style="margin-right: 8px;"></i> Flower Plants</a>
    <a href="Fruit_Plants_Shop.php"><i class="fa-solid fa-apple-whole" style="margin-right: 8px;"></i> Fruit Plants</a>
    <a href="Medicinal_Plants_Shop.php"><i class="fa-solid fa-seedling" style="margin-right: 8px;"></i> Medicinal Plants</a>
    <a href="plant_diseases_100.php"><i class="fa-solid fa-notes-medical" style="margin-right: 8px;"></i> Plant Diseases &amp; Cure</a>
    <a href="air_purification_plants_detailed.php"><i class="fa-solid fa-wind" style="margin-right: 8px;"></i> Purification Plants</a>
  </div>
</div>

<footer>
  &copy; 2025 Online Plant Nursery. All rights reserved.
</footer>

</body>
</html>
