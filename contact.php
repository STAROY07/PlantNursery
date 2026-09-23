<?php
$sent = isset($_GET['sent']) && $_GET['sent'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FeedBack | Online Plant Nursery</title>

<style>
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: transparent; /* 🔥 use global background */
}

/* NAVBAR */
nav {
    background: rgba(46,125,50,0.85);
    padding: 12px;
    text-align: center;
    backdrop-filter: blur(8px);
    position: relative;
}

nav a {
    color: white;
    margin: 0 15px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

nav a:hover {
    background: rgba(255,255,255,0.2);
    border-radius: 6px;
}

/* <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-right: 5px; vertical-align: text-bottom;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> PROFILE ICON */
.wishlist-icon-wrap {
  position: absolute;
  right: 72px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 999;
}
.wishlist-icon-btn {
  background: rgba(255,255,255,0.2);
  border: 2px solid rgba(255,255,255,0.6);
  border-radius: 50%;
  width: 42px;
  height: 42px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  transition: background 0.2s, transform 0.2s;
  color: #fff;
  text-decoration: none;
  user-select: none;
}
.wishlist-icon-btn:hover {
  background: rgba(255,255,255,0.35);
  transform: scale(1.1);
  color: #ff6b8a;
}
.profile-icon-wrap {
  position: absolute;
  right: 18px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 999;
}
.profile-icon-btn {
  background: rgba(255,255,255,0.2);
  border: 2px solid rgba(255,255,255,0.6);
  border-radius: 50%;
  width: 42px;
  height: 42px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  transition: background 0.2s, transform 0.2s;
  color: #fff;
}
.profile-icon-btn:hover { background: rgba(255,255,255,0.35); transform: scale(1.1); }
.profile-dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 50px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.18);
  min-width: 150px;
  overflow: hidden;
  z-index: 1000;
}
.profile-dropdown a {
  display: block;
  padding: 12px 18px;
  color: #2e7d32 !important;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
  background: #fff !important;
  transition: background 0.2s;
  border-bottom: 1px solid #f0f0f0;
  margin: 0 !important;
}
.profile-dropdown a:last-child { border-bottom: none; }
.profile-dropdown a:hover { background: #e8f5e9 !important; }
.profile-dropdown.open { display: block; }
@keyframes dropFade {
  from { opacity: 0; transform: translateY(-8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.profile-dropdown { animation: dropFade 0.18s ease; }

/* 🔥 FIXED HEADER (IMPORTANT) */
header {
    text-align: center;
    padding: 20px;
    font-size: 28px;
    font-weight: bold;

    color: #ffffff; /* 🔥 white for visibility */

    background: rgba(0, 80, 0, 0.5); /* dark glass */
    display: inline-block;
    margin: 20px auto;
    border-radius: 12px;
    padding: 12px 30px;

    backdrop-filter: blur(8px);

    box-shadow: 0 5px 20px rgba(0,0,0,0.3);

    text-shadow: 2px 2px 10px rgba(0,0,0,0.9);
}

/* CENTER HEADER */
header {
    display: block;
    width: fit-content;
}

/* CARD */
.container {
    max-width: 650px;
    margin: 30px auto;

    background: rgba(255,255,255,0.95);
    padding: 30px;
    border-radius: 16px;

    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    backdrop-filter: blur(10px);
}

/* INPUTS */
input, textarea {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    margin-bottom: 18px;
    border: none;
    border-radius: 8px;
    font-size: 15px;

    background: rgba(255,255,255,0.95);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);

    transition: 0.3s;
}

input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 8px rgba(46,125,50,0.5);
}

/* BUTTON */
input[type=submit] {
    background: linear-gradient(45deg, #43a047, #66bb6a);
    color: white;
    font-size: 16px;
    cursor: pointer;
    border: none;
    font-weight: 600;
    transition: 0.3s;
}

input[type=submit]:hover {
    transform: scale(1.03);
}

/* SUCCESS BOX */
.success-box {
    background: rgba(200,255,220,0.9);
    padding: 12px;
    border-left: 5px solid #2e7d32;
    border-radius: 8px;
    margin-bottom: 20px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* RATING */
.rating {
    direction: rtl;
    font-size: 30px;
    display: inline-flex;
}

.rating input { display: none; }

.rating label {
    color: #ccc;
    cursor: pointer;
    transition: 0.2s;
}

.rating label:hover,
.rating label:hover ~ label {
    color: gold;
}

.rating input:checked ~ label {
    color: gold;
}

/* CENTER RATING */
.rating-box {
    text-align: center;
    margin-bottom: 15px;
}
</style>
</head>
<?php include 'global_style.php'; ?>

<body>

<nav>
  <a href="index.php">🏠 Home</a>
  <a href="Flower_Plants_Shop.php">🌸 Flower Plants</a>
  <a href="Fruit_Plants_Shop.php">🍋 Fruit Plants</a>
  <a href="Medicinal_Plants_Shop.php">🌿 Medicinal Plants</a>
  <a href="air_purification_plants_detailed.php">💨 Air Plants</a>
  <a href="cart.php">🛒 Cart</a>
  <a href="help_center/help_center.php">🛠️ Help Center</a>

  <!-- Wishlist Icon -->
  <div class="wishlist-icon-wrap">
    <a href="wishlist.php" class="wishlist-icon-btn" title="My Wishlist">
      <span id="wishlist-badge" class="wishlist-badge" style="display:none;">0</span>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
    </a>
  </div>

  <!-- Profile Icon Top Right -->
  <div class="profile-icon-wrap" id="profileWrap">
    <span class="profile-icon-btn" id="profileBtn" title="My Account" onclick="toggleProfileMenu(event)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 22px; height: 22px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
    <div class="profile-dropdown" id="profileDropdown">
      <a href="profile.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-right: 5px; vertical-align: text-bottom;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> My Profile</a>
      <a href="orders.php">📦 My Orders</a>
      <a href="transaction_history.php">💳 Transactions</a>
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>
</nav>
<script>
function toggleProfileMenu(e) {
  e.stopPropagation();
  document.getElementById('profileDropdown').classList.toggle('open');
}
document.addEventListener('click', function(e) {
  var wrap = document.getElementById('profileWrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('profileDropdown').classList.remove('open');
  }
});
</script>

<header>🌿 Feedback - Online Plant Nursery</header>

<div class="container">

<?php if($sent): ?>
<div class="success-box" id="successBox">
    <span>✔ Your message was sent successfully</span>
    <button onclick="document.getElementById('successBox').style.display='none'" style="border:none;background:none;font-size:18px;cursor:pointer;">×</button>
</div>

<script>
setTimeout(() => {
    let box = document.getElementById('successBox');
    if (box) box.style.display = 'none';
}, 4000);
</script>
<?php endif; ?>

<form method="post" action="contact_process.php">

<label>Name</label>
<input type="text" name="name" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Message</label>
<textarea name="message" rows="5" required></textarea>

<div class="rating-box">
    <label>Rate Us</label><br>
    <div class="rating">
      <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
      <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
      <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
      <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
      <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
    </div>
</div>

<input type="submit" value="Send Message">

</form>
</div>

</body>
</html>