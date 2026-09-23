<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Flower Plants Shop</title>
  <style>
body {
  font-family: Arial, sans-serif;
  background: transparent; /* 🔥 important (to show global background) */
}

/* TITLE */
h1 {
  text-align: center;
  background: rgba(76, 175, 80, 0.7);
  color: white;
  padding: 20px;
  border-radius: 10px;
  backdrop-filter: blur(8px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* CONTAINER */
.container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding: 20px;
}

/* 🔥 CARD IMPROVED */
.plant-card {
  width: 260px;
  background: rgba(255,255,255,0.9);
  margin: 10px;
  padding: 15px;
  border-radius: 12px;
  text-align: center;

  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  transition: all 0.3s ease;
}

/* 🔥 HOVER EFFECT */
.plant-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 12px 30px rgba(0,0,0,0.3);
}

/* IMAGE */
.plant-card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 8px;
  transition: 0.3s;
}

/* 🔥 IMAGE ZOOM */
.plant-card:hover img {
  transform: scale(1.05);
}

/* TEXT */
.plant-card h3 {
  margin: 10px 0 5px;
  color: #2e7d32;
}

.plant-card p {
  font-size: 14px;
  color: #555;
  margin: 5px 0;
}

/* PRICE */
.price {
  color: #2e7d32;
  font-weight: bold;
  margin: 8px 0;
  font-size: 16px;
}

/* 🔥 BUTTON IMPROVED */
button {
  background: linear-gradient(45deg, #43a047, #66bb6a);
  color: white;
  padding: 10px 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
  font-weight: bold;

  transition: all 0.3s ease;
}

/* BUTTON HOVER */
button:hover {
  transform: scale(1.05);
  opacity: 0.9;
}

/* NAV LINKS */
nav a {
  color: white;
  text-decoration: none;
  margin: 0 10px;
}

/* SUCCESS MESSAGE */
.success-msg {
  background: #d4edda;
  color: #155724;
  padding: 12px;
  margin: 15px auto;
  border-radius: 8px;
  text-align: center;
  width: 60%;
  font-weight: 500;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style></head>
<script>
setTimeout(function(){
    var msg = document.getElementById("cartMessage");
    if(msg){
        msg.style.transition = "opacity 0.5s ease";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 3000); // 3000ms = 3 seconds
</script>
<?php include 'global_style.php'; ?>
<body>

<?php
if(isset($_SESSION['cart_msg'])){
    echo "<div id='cartMessage' class='success-msg'>".$_SESSION['cart_msg']."</div>";
    unset($_SESSION['cart_msg']);
}
?>

<!-- SEARCH BAR (UNCHANGED) -->
<div style="text-align: center; margin-top: 20px;">
<form action="SearchFlower.php" method="get" style="display: inline-block;">
  <input type="text" id="q" name="q" placeholder="Search flower" required
         style="padding: 10px; width: 250px; border: 1px solid #ccc; border-radius: 5px;">
  <button type="submit"
          style="padding: 15px 15px; background:#4CAF50; color:white; border:none; border-radius:5px;">
    Search
  </button>
</form>
</div>

<!-- NAV BAR -->
<style>
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
  text-decoration: none;
  user-select: none;
}
.profile-icon-btn:hover {
  background: rgba(255,255,255,0.35);
  transform: scale(1.1);
}
.profile-dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 52px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.22);
  min-width: 160px;
  overflow: hidden;
  z-index: 1000;
  animation: dropFade 0.18s ease;
}
@keyframes dropFade {
  from { opacity: 0; transform: translateY(-8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.profile-dropdown.open { display: block; }
.profile-dropdown a {
  display: block;
  padding: 12px 18px;
  color: #2e7d32 !important;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
  background: #fff !important;
  transition: background 0.15s;
  border-bottom: 1px solid #f0f0f0;
}
.profile-dropdown a:last-child { border-bottom: none; }
.profile-dropdown a:hover { background: #e8f5e9 !important; }
</style>
<nav style="background-color: #4CAF50; padding: 10px; text-align: center; position: relative;">
  <a href="index.php">🏠 Home</a>
  <a href="Flower_Plants_Shop.php">🌸 Flower Plants</a>
  <a href="Fruit_Plants_Shop.php">🍋 Fruit Plants</a>
  <a href="Medicinal_Plants_Shop.php">🌿 Medicinal Plants</a>
  <a href="air_purification_plants_detailed.php">💨 Air Purifying Plants</a>
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
  var dd = document.getElementById('profileDropdown');
  dd.classList.toggle('open');
}
document.addEventListener('click', function(e) {
  var wrap = document.getElementById('profileWrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('profileDropdown').classList.remove('open');
  }
});
</script>



<h1>Flower Plants - Online Shop</h1>
<div class="container">

 <!-- 1 -->
 <div class="plant-card">

<!-- Add to Cart -->
<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_1.jpeg" alt="Rose">
  <h3>Rose</h3>
  <p>Classic fragrant blooms, perfect for bouquets and gardens.</p>
  <p class="price">₹150</p>

  <input type="hidden" name="plant_id" value="1">
  <button type="submit">Add to Cart</button>
</form>

<!-- Add to Wishlist -->
<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="1">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>


<!-- PLANT 2 : Lotus -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_3.jpeg" alt="Lotus">
    <h3>Lotus</h3>
    <p>Elegant aquatic flower symbolizing purity and spirituality.</p>
    <p class="price">₹120</p>
    <input type="hidden" name="plant_id" value="2">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="2">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- PLANT 3 : Sunflower -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_4.jpeg" alt="Sunflower">
    <h3>Sunflower</h3>
    <p>Large sunny blooms that turn toward the sun.</p>
    <p class="price">₹80</p>
    <input type="hidden" name="plant_id" value="3">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="3">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- PLANT 4 : Marigold -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_2.jpeg" alt="Marigold">
    <h3>Marigold</h3>
    <p>Bright, festive flower commonly used in garlands and rituals.</p>
    <p class="price">₹40</p>
    <input type="hidden" name="plant_id" value="4">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="4">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- PLANT 5 : Hibiscus -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_5.jpeg" alt="Hibiscus">
    <h3>Hibiscus</h3>
    <p>Large tropical flowers in vivid colors, often used medicinally.</p>
    <p class="price">₹60</p>
    <input type="hidden" name="plant_id" value="5">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="5">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>
<!-- 6 : Jasmine -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_6.jpeg" alt="Jasmine">
    <h3>Jasmine</h3>
    <p>Small, highly fragrant white flowers used in perfumery.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="6">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="6">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 7 : Lavender -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_7.jpeg" alt="Lavender">
    <h3>Lavender</h3>
    <p>Soothing fragrant spikes used for scent and calming teas.</p>
    <p class="price">₹90</p>
    <input type="hidden" name="plant_id" value="7">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="7">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 8 : Daisy -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_8.jpeg" alt="Daisy">
    <h3>Daisy</h3>
    <p>Simple cheerful blooms with white petals and yellow centers.</p>
    <p class="price">₹30</p>
    <input type="hidden" name="plant_id" value="8">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="8">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 9 : Tulip -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_9.jpeg" alt="Tulip">
    <h3>Tulip</h3>
    <p>Spring-blooming cup-shaped flowers in many colours.</p>
    <p class="price">₹100</p>
    <input type="hidden" name="plant_id" value="9">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="9">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 10 : Orchid -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_10.jpeg" alt="Orchid">
    <h3>Orchid</h3>
    <p>Exotic, long-lasting blooms popular as indoor plants.</p>
    <p class="price">₹250</p>
    <input type="hidden" name="plant_id" value="10">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="10">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

 <!-- 11 : Chrysanthemum -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_11.jpeg" alt="Chrysanthemum">
  <h3>Chrysanthemum</h3>
  <p>Autumn-blooming flowers commonly used in bouquets and displays.</p>
  <p class="price">₹65</p>
  <input type="hidden" name="plant_id" value="11">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="11">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 12 : Dahlia -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_12.jpeg" alt="Dahlia">
  <h3>Dahlia</h3>
  <p>Layered, sculptural blooms available in many colours and sizes.</p>
  <p class="price">₹70</p>
  <input type="hidden" name="plant_id" value="12">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="12">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 13 : Lily -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_13.jpeg" alt="Lily">
  <h3>Lily</h3>
  <p>Elegant trumpet-shaped flowers symbolizing purity and beauty.</p>
  <p class="price">₹120</p>
  <input type="hidden" name="plant_id" value="13">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="13">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 14 : Peony -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_14.jpeg" alt="Peony">
  <h3>Peony</h3>
  <p>Large, lush blooms highly valued in ornamental gardens.</p>
  <p class="price">₹180</p>
  <input type="hidden" name="plant_id" value="14">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="14">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 15 : Carnation -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_15.jpeg" alt="Carnation">
  <h3>Carnation</h3>
  <p>Frilly, long-lasting flowers widely used in bouquets.</p>
  <p class="price">₹60</p>
  <input type="hidden" name="plant_id" value="15">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="15">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 16 : Zinnia -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_16.jpeg" alt="Zinnia">
  <h3>Zinnia</h3>
  <p>Bright, long-lasting flowers that attract butterflies.</p>
  <p class="price">₹35</p>
  <input type="hidden" name="plant_id" value="16">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="16">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 17 : Pansy -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_17.jpeg" alt="Pansy">
  <h3>Pansy</h3>
  <p>Cool-season flowers with charming face-like blooms.</p>
  <p class="price">₹30</p>
  <input type="hidden" name="plant_id" value="17">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="17">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 18 : Petunia -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_18.jpeg" alt="Petunia">
  <h3>Petunia</h3>
  <p>Showy trumpet-shaped flowers ideal for pots and hanging baskets.</p>
  <p class="price">₹45</p>
  <input type="hidden" name="plant_id" value="18">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="18">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 19 : Begonia -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_19.jpeg" alt="Begonia">
  <h3>Begonia</h3>
  <p>Fleshy-leaved plant with colourful flowers suitable for indoors.</p>
  <p class="price">₹60</p>
  <input type="hidden" name="plant_id" value="19">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="19">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 20 : Gardenia -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_20.jpeg" alt="Gardenia">
  <h3>Gardenia</h3>
  <p>Highly fragrant white blooms often used in perfumes.</p>
  <p class="price">₹140</p>
  <input type="hidden" name="plant_id" value="20">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="20">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>


  <!-- 21 : Camellia -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_21.jpeg" alt="Camellia">
  <h3>Camellia</h3>
  <p>Glossy-leaved shrub with beautiful rose-like flowers.</p>
  <p class="price">₹160</p>
  <input type="hidden" name="plant_id" value="21">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="21">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 22 : Frangipani -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_22.jpeg" alt="Frangipani">
  <h3>Frangipani (Plumeria)</h3>
  <p>Tropical fragrant flowers commonly used in garlands.</p>
  <p class="price">₹95</p>
  <input type="hidden" name="plant_id" value="22">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="22">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 23 : Bougainvillea -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_23.jpeg" alt="Bougainvillea">
  <h3>Bougainvillea</h3>
  <p>Hardy climber with bright colourful bracts year-round.</p>
  <p class="price">₹75</p>
  <input type="hidden" name="plant_id" value="23">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="23">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 24 : Morning Glory -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_24.jpeg" alt="Morning Glory">
  <h3>Morning Glory</h3>
  <p>Fast-growing climber with trumpet-shaped morning blooms.</p>
  <p class="price">₹35</p>
  <input type="hidden" name="plant_id" value="24">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="24">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 25 : Hydrangea -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_25.jpeg" alt="Hydrangea">
  <h3>Hydrangea</h3>
  <p>Large round flower clusters in soft pastel colours.</p>
  <p class="price">₹150</p>
  <input type="hidden" name="plant_id" value="25">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="25">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 26 : Magnolia -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_26.jpeg" alt="Magnolia">
  <h3>Magnolia</h3>
  <p>Large, fragrant blossoms on ornamental trees and shrubs.</p>
  <p class="price">₹200</p>
  <input type="hidden" name="plant_id" value="26">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="26">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 27 : Snowdrop -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_27.jpeg" alt="Snowdrop">
  <h3>Snowdrop</h3>
  <p>Delicate white flowers that bloom early in spring.</p>
  <p class="price">₹45</p>
  <input type="hidden" name="plant_id" value="27">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="27">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 28 : Bluebell -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_28.jpeg" alt="Bluebell">
  <h3>Bluebell</h3>
  <p>Bell-shaped blue flowers carpeting gardens in spring.</p>
  <p class="price">₹55</p>
  <input type="hidden" name="plant_id" value="28">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="28">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 29 : Foxglove -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_29.jpeg" alt="Foxglove">
  <h3>Foxglove</h3>
  <p>Tall spikes of tubular flowers attractive to bees.</p>
  <p class="price">₹70</p>
  <input type="hidden" name="plant_id" value="29">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="29">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 30 : Aster -->
<div class="plant-card">

<form action="add_to_cart.php" method="post">
  <img src="Images/flower/flower_plant_30.jpeg" alt="Aster">
  <h3>Aster</h3>
  <p>Star-shaped flowers that brighten late-season gardens.</p>
  <p class="price">₹55</p>
  <input type="hidden" name="plant_id" value="30">
  <button type="submit">Add to Cart</button>
</form>

<form action="add_to_wishlist.php" method="post">
  <input type="hidden" name="plant_id" value="30">
  <button type="submit">❤️ Add to Wishlist</button>
</form>

</div>

<!-- 31 : Verbena -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_31.jpeg" alt="Verbena">
    <h3>Verbena</h3>
    <p>Low-growing plant with clusters of small colourful flowers.</p>
    <p class="price">₹35</p>
    <input type="hidden" name="plant_id" value="31">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="31">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 32 : Calendula -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_32.jpeg" alt="Calendula">
    <h3>Calendula</h3>
    <p>Also called pot marigold, used in herbal remedies.</p>
    <p class="price">₹40</p>
    <input type="hidden" name="plant_id" value="32">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="32">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 33 : Nasturtium -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_33.jpeg" alt="Nasturtium">
    <h3>Nasturtium</h3>
    <p>Bright edible flowers with a trailing growth habit.</p>
    <p class="price">₹30</p>
    <input type="hidden" name="plant_id" value="33">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="33">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 34 : Snapdragon -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_34.jpeg" alt="Snapdragon">
    <h3>Snapdragon</h3>
    <p>Tall spikes of colourful flowers shaped like dragon mouths.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="34">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="34">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 35 : Geranium -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_35.jpeg" alt="Geranium">
    <h3>Geranium</h3>
    <p>Popular bedding plant with clusters of bright flowers.</p>
    <p class="price">₹55</p>
    <input type="hidden" name="plant_id" value="35">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="35">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 36 : Cosmos -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_36.jpeg" alt="Cosmos">
    <h3>Cosmos</h3>
    <p>Delicate, daisy-like flowers that attract pollinators.</p>
    <p class="price">₹25</p>
    <input type="hidden" name="plant_id" value="36">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="36">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 37 : Black-eyed Susan -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_37.jpeg" alt="Black-eyed Susan">
    <h3>Black-eyed Susan</h3>
    <p>Golden daisy-like flowers with dark central cones.</p>
    <p class="price">₹45</p>
    <input type="hidden" name="plant_id" value="37">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="37">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 38 : Bird of Paradise -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_38.jpeg" alt="Bird of Paradise">
    <h3>Bird of Paradise</h3>
    <p>Striking tropical flowers resembling a bird in flight.</p>
    <p class="price">₹220</p>
    <input type="hidden" name="plant_id" value="38">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="38">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 39 : Anemone -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_39.jpeg" alt="Anemone">
    <h3>Anemone</h3>
    <p>Poppy-like blooms that add bright colour to gardens.</p>
    <p class="price">₹60</p>
    <input type="hidden" name="plant_id" value="39">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="39">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 40 : Freesia -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_40.jpeg" alt="Freesia">
    <h3>Freesia</h3>
    <p>Delicate, fragrant funnel-shaped flowers on arching stems.</p>
    <p class="price">₹65</p>
    <input type="hidden" name="plant_id" value="40">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="40">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 41 : Gladiolus -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_41.jpeg" alt="Gladiolus">
    <h3>Gladiolus</h3>
    <p>Tall flower spikes popular for cut-flower arrangements.</p>
    <p class="price">₹70</p>
    <input type="hidden" name="plant_id" value="41">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="41">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 42 : Buttercup -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_42.jpeg" alt="Buttercup">
    <h3>Buttercup</h3>
    <p>Bright yellow flowers that bloom in spring meadows.</p>
    <p class="price">₹35</p>
    <input type="hidden" name="plant_id" value="42">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="42">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 43 : Lupine -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_43.jpeg" alt="Lupine">
    <h3>Lupine</h3>
    <p>Spire-like clusters of flowers in cool shades.</p>
    <p class="price">₹80</p>
    <input type="hidden" name="plant_id" value="43">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="43">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 44 : Sweet Pea -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_44.jpeg" alt="Sweet Pea">
    <h3>Sweet Pea</h3>
    <p>Fragrant climbing flowers with delicate petals.</p>
    <p class="price">₹40</p>
    <input type="hidden" name="plant_id" value="44">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="44">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 45 : Iris -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_45.jpeg" alt="Iris">
    <h3>Iris</h3>
    <p>Showy blooms with distinctive patterns and colours.</p>
    <p class="price">₹95</p>
    <input type="hidden" name="plant_id" value="45">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="45">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 46 : Azalea -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_46.jpeg" alt="Azalea">
    <h3>Azalea</h3>
    <p>Colourful spring-blooming shrub popular in gardens.</p>
    <p class="price">₹130</p>
    <input type="hidden" name="plant_id" value="46">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="46">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 47 : Heather -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_47.jpeg" alt="Heather">
    <h3>Heather</h3>
    <p>Evergreen shrub with tiny bell-shaped flowers.</p>
    <p class="price">₹70</p>
    <input type="hidden" name="plant_id" value="47">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="47">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 48 : Goldenrod -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_48.jpeg" alt="Goldenrod">
    <h3>Goldenrod</h3>
    <p>Bright yellow flowers that attract pollinators.</p>
    <p class="price">₹45</p>
    <input type="hidden" name="plant_id" value="48">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="48">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 49 : Vinca -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_49.jpeg" alt="Vinca">
    <h3>Vinca (Periwinkle)</h3>
    <p>Low-growing flowering plant with glossy leaves.</p>
    <p class="price">₹25</p>
    <input type="hidden" name="plant_id" value="49">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="49">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 50 : Sweet William -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_50.jpeg" alt="Sweet William">
    <h3>Sweet William</h3>
    <p>Clustered fragrant flowers used in cottage gardens.</p>
    <p class="price">₹60</p>
    <input type="hidden" name="plant_id" value="50">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="50">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 51 : Yarrow -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_51.jpeg" alt="Yarrow">
    <h3>Yarrow</h3>
    <p>Flat-topped clusters of hardy medicinal flowers.</p>
    <p class="price">₹40</p>
    <input type="hidden" name="plant_id" value="51">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="51">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 52 : Salvia -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_52.jpeg" alt="Salvia">
    <h3>Salvia</h3>
    <p>Spiky blooms loved by bees and hummingbirds.</p>
    <p class="price">₹45</p>
    <input type="hidden" name="plant_id" value="52">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="52">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 53 : Columbine -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_53.jpeg" alt="Columbine">
    <h3>Columbine</h3>
    <p>Graceful nodding flowers with unique spurs.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="53">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="53">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 54 : Canna Lily -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_54.jpeg" alt="Canna Lily">
    <h3>Canna Lily</h3>
    <p>Bold tropical foliage with vibrant flowers.</p>
    <p class="price">₹90</p>
    <input type="hidden" name="plant_id" value="54">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="54">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 55 : Coreopsis -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_55.jpeg" alt="Coreopsis">
    <h3>Coreopsis</h3>
    <p>Easy-care daisy-like flowers with long blooms.</p>
    <p class="price">₹35</p>
    <input type="hidden" name="plant_id" value="55">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="55">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 56 : Delphinium -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_56.jpeg" alt="Delphinium">
    <h3>Delphinium</h3>
    <p>Tall dramatic spikes in blue and purple shades.</p>
    <p class="price">₹80</p>
    <input type="hidden" name="plant_id" value="56">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="56">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 57 : Coneflower -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_57.jpeg" alt="Coneflower">
    <h3>Coneflower (Echinacea)</h3>
    <p>Medicinal flowering plant with daisy-like blooms.</p>
    <p class="price">₹60</p>
    <input type="hidden" name="plant_id" value="57">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="57">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 58 : Maranta Flower -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_58.jpeg" alt="Maranta Flower">
    <h3>Maranta Flower</h3>
    <p>Decorative foliage plant that occasionally flowers indoors.</p>
    <p class="price">₹85</p>
    <input type="hidden" name="plant_id" value="58">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="58">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 59 : Tuberose -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_59.jpeg" alt="Tuberose">
    <h3>Tuberose (Rajnigandha)</h3>
    <p>Strongly fragrant white flowers used in garlands.</p>
    <p class="price">₹95</p>
    <input type="hidden" name="plant_id" value="59">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="59">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 60 : Ixora -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_60.jpeg" alt="Ixora">
    <h3>Ixora</h3>
    <p>Tropical shrub with dense clusters of bright flowers.</p>
    <p class="price">₹70</p>
    <input type="hidden" name="plant_id" value="60">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="60">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 61 : Kalanchoe -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_61.jpeg" alt="Kalanchoe">
    <h3>Kalanchoe</h3>
    <p>Succulent plant with long-lasting colourful blooms.</p>
    <p class="price">₹60</p>
    <input type="hidden" name="plant_id" value="61">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="61">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 62 : Allamanda -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_62.jpeg" alt="Allamanda">
    <h3>Allamanda</h3>
    <p>Golden trumpet-shaped flowers on a vigorous climber.</p>
    <p class="price">₹75</p>
    <input type="hidden" name="plant_id" value="62">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="62">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 63 : Oleander -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_63.jpeg" alt="Oleander">
    <h3>Oleander</h3>
    <p>Hardy shrub with showy clusters of flowers.</p>
    <p class="price">₹80</p>
    <input type="hidden" name="plant_id" value="63">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="63">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 64 : Trumpet Vine -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_64.jpeg" alt="Trumpet Vine">
    <h3>Trumpet Vine</h3>
    <p>Fast-growing climber with trumpet-shaped blooms.</p>
    <p class="price">₹60</p>
    <input type="hidden" name="plant_id" value="64">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="64">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 65 : Passion Flower -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_65.jpeg" alt="Passion Flower">
    <h3>Passion Flower</h3>
    <p>Exotic, intricate flowers on a vigorous vine.</p>
    <p class="price">₹100</p>
    <input type="hidden" name="plant_id" value="65">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="65">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 66 : Bleeding Heart -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_66.jpeg" alt="Bleeding Heart">
    <h3>Bleeding Heart</h3>
    <p>Heart-shaped pink flowers on arching stems for shade gardens.</p>
    <p class="price">₹75</p>
    <input type="hidden" name="plant_id" value="66">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="66">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 67 : Gloxinia -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_67.jpeg" alt="Gloxinia">
    <h3>Gloxinia</h3>
    <p>Velvety bell-shaped flowers ideal for indoor pots.</p>
    <p class="price">₹95</p>
    <input type="hidden" name="plant_id" value="67">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="67">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 68 : Gaillardia -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_68.jpeg" alt="Gaillardia">
    <h3>Gaillardia</h3>
    <p>Also called blanket flower with bright red and yellow blooms.</p>
    <p class="price">₹40</p>
    <input type="hidden" name="plant_id" value="68">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="68">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 69 : Scabiosa -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_69.jpeg" alt="Scabiosa">
    <h3>Scabiosa</h3>
    <p>Pin-cushion flowers loved by bees and butterflies.</p>
    <p class="price">₹55</p>
    <input type="hidden" name="plant_id" value="69">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="69">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 70 : Heliconia -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_70.jpeg" alt="Heliconia">
    <h3>Heliconia</h3>
    <p>Tropical exotic bracts used in floral arrangements.</p>
    <p class="price">₹200</p>
    <input type="hidden" name="plant_id" value="70">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="70">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 71 : Protea -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_71.jpeg" alt="Protea">
    <h3>Protea</h3>
    <p>Unique long-lasting blooms native to South Africa.</p>
    <p class="price">₹220</p>
    <input type="hidden" name="plant_id" value="71">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="71">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 72 : Stephanotis -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_72.jpeg" alt="Stephanotis">
    <h3>Stephanotis</h3>
    <p>Highly fragrant white flowers often used in weddings.</p>
    <p class="price">₹160</p>
    <input type="hidden" name="plant_id" value="72">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="72">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 73 : Wisteria -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_73.jpeg" alt="Wisteria">
    <h3>Wisteria</h3>
    <p>Drooping clusters of lavender flowers on vigorous vines.</p>
    <p class="price">₹180</p>
    <input type="hidden" name="plant_id" value="73">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="73">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 74 : Sedum -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_74.jpeg" alt="Sedum">
    <h3>Sedum</h3>
    <p>Succulent groundcover with clusters of tiny flowers.</p>
    <p class="price">₹35</p>
    <input type="hidden" name="plant_id" value="74">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="74">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 75 : Phlox -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_75.jpeg" alt="Phlox">
    <h3>Phlox</h3>
    <p>Fragrant summer-blooming clusters for borders and beds.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="75">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="75">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 76 : Wallflower -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_76.jpeg" alt="Wallflower">
    <h3>Wallflower</h3>
    <p>Fragrant cool-season flowers often grown in borders.</p>
    <p class="price">₹45</p>
    <input type="hidden" name="plant_id" value="76">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="76">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 77 : Primrose -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_77.jpeg" alt="Primrose">
    <h3>Primrose</h3>
    <p>Early spring flowers with soft pastel-coloured blooms.</p>
    <p class="price">₹40</p>
    <input type="hidden" name="plant_id" value="77">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="77">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 78 : Poppy -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_78.jpeg" alt="Poppy">
    <h3>Poppy</h3>
    <p>Showy flowers with delicate, papery petals.</p>
    <p class="price">₹55</p>
    <input type="hidden" name="plant_id" value="78">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="78">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 79 : Ranunculus -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_79.jpeg" alt="Ranunculus">
    <h3>Ranunculus</h3>
    <p>Layered rose-like blooms popular in cut-flower arrangements.</p>
    <p class="price">₹55</p>
    <input type="hidden" name="plant_id" value="79">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="79">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 80 : Snowflake Flower -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_80.jpeg" alt="Snowflake Flower">
    <h3>Snowflake Flower</h3>
    <p>Delicate white bell-shaped blooms appearing in spring.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="80">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="80">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 81 : Torch Ginger -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_81.jpeg" alt="Torch Ginger">
    <h3>Torch Ginger</h3>
    <p>Bold tropical flower used for dramatic floral displays.</p>
    <p class="price">₹210</p>
    <input type="hidden" name="plant_id" value="81">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="81">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 82 : Chrysanthemum Spider -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_82.jpeg" alt="Chrysanthemum Spider">
    <h3>Chrysanthemum Spider</h3>
    <p>Unique chrysanthemum variety with long, spidery petals.</p>
    <p class="price">₹80</p>
    <input type="hidden" name="plant_id" value="82">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="82">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 83 : Blue Sage -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_83.jpeg" alt="Blue Sage">
    <h3>Blue Sage</h3>
    <p>Fragrant blue flower spikes that attract pollinators.</p>
    <p class="price">₹55</p>
    <input type="hidden" name="plant_id" value="83">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="83">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 84 : Catmint -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_84.jpeg" alt="Catmint">
    <h3>Catmint</h3>
    <p>Aromatic foliage with soft lavender-blue flowers.</p>
    <p class="price">₹40</p>
    <input type="hidden" name="plant_id" value="84">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="84">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 85 : Million Bells -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_85.jpeg" alt="Million Bells">
    <h3>Million Bells</h3>
    <p>Trailing plant covered with masses of small bell-shaped flowers.</p>
    <p class="price">₹45</p>
    <input type="hidden" name="plant_id" value="85">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="85">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 86 : Lantana -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_86.jpeg" alt="Lantana">
    <h3>Lantana</h3>
    <p>Heat-tolerant plant with clusters of multi-coloured flowers.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="86">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="86">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 87 : Sweet Alyssum -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_87.jpeg" alt="Sweet Alyssum">
    <h3>Sweet Alyssum</h3>
    <p>Tiny fragrant flowers forming low-growing carpets.</p>
    <p class="price">₹30</p>
    <input type="hidden" name="plant_id" value="87">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="87">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 88 : Chocolate Cosmos -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_88.jpeg" alt="Chocolate Cosmos">
    <h3>Chocolate Cosmos</h3>
    <p>Dark maroon flowers with a light chocolate fragrance.</p>
    <p class="price">₹95</p>
    <input type="hidden" name="plant_id" value="88">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="88">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 89 : Hellebore -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_89.jpeg" alt="Hellebore">
    <h3>Hellebore</h3>
    <p>Winter-blooming perennial with nodding cup-shaped flowers.</p>
    <p class="price">₹120</p>
    <input type="hidden" name="plant_id" value="89">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="89">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 90 : Obedient Plant -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_90.jpeg" alt="Obedient Plant">
    <h3>Obedient Plant</h3>
    <p>Spike-like flowers that stay in place when gently moved.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="90">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="90">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 91 : Spider Lily -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_91.jpeg" alt="Spider Lily">
    <h3>Spider Lily</h3>
    <p>Elegant spidery flowers blooming in late summer.</p>
    <p class="price">₹110</p>
    <input type="hidden" name="plant_id" value="91">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="91">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 92 : Star Jasmine -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_92.jpeg" alt="Star Jasmine">
    <h3>Star Jasmine</h3>
    <p>Highly fragrant white star-shaped flowers on climbers.</p>
    <p class="price">₹90</p>
    <input type="hidden" name="plant_id" value="92">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="92">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 93 : Water Hyacinth -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_93.jpeg" alt="Water Hyacinth">
    <h3>Water Hyacinth</h3>
    <p>Floating aquatic plant with attractive lavender flowers.</p>
    <p class="price">₹60</p>
    <input type="hidden" name="plant_id" value="93">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="93">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 94 : Tecoma -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_94.jpeg" alt="Tecoma">
    <h3>Tecoma</h3>
    <p>Fast-growing shrub with trumpet-shaped orange flowers.</p>
    <p class="price">₹70</p>
    <input type="hidden" name="plant_id" value="94">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="94">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 95 : Ruellia -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_95.jpeg" alt="Ruellia">
    <h3>Ruellia</h3>
    <p>Purple tubular flowers also known as wild petunia.</p>
    <p class="price">₹45</p>
    <input type="hidden" name="plant_id" value="95">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="95">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 96 : Scarlet Sage -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_96.jpeg" alt="Scarlet Sage">
    <h3>Scarlet Sage</h3>
    <p>Bright red tubular flowers that attract hummingbirds.</p>
    <p class="price">₹55</p>
    <input type="hidden" name="plant_id" value="96">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="96">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 97 : Globe Amaranth -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_97.jpeg" alt="Globe Amaranth">
    <h3>Globe Amaranth (Gomphrena)</h3>
    <p>Round papery blooms that retain colour when dried.</p>
    <p class="price">₹35</p>
    <input type="hidden" name="plant_id" value="97">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="97">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 98 : White Ginger Lily -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_98.jpeg" alt="White Ginger Lily">
    <h3>White Ginger Lily</h3>
    <p>Highly fragrant white flowers on tropical ginger plants.</p>
    <p class="price">₹140</p>
    <input type="hidden" name="plant_id" value="98">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="98">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 99 : Mexican Sunflower -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_99.jpeg" alt="Mexican Sunflower">
    <h3>Mexican Sunflower</h3>
    <p>Bold orange daisy-like flowers attracting bees and butterflies.</p>
    <p class="price">₹45</p>
    <input type="hidden" name="plant_id" value="99">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="99">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>

<!-- 100 : African Daisy -->
<div class="plant-card">

  <form action="add_to_cart.php" method="post">
    <img src="Images/flower/flower_plant_100.jpeg" alt="African Daisy">
    <h3>African Daisy</h3>
    <p>Vibrant daisy flowers with striking centres for sunny gardens.</p>
    <p class="price">₹50</p>
    <input type="hidden" name="plant_id" value="100">
    <button type="submit">Add to Cart</button>
  </form>

  <form action="add_to_wishlist.php" method="post">
    <input type="hidden" name="plant_id" value="100">
    <button type="submit">❤️ Add to Wishlist</button>
  </form>

</div>
</div>


</body>
</html>
