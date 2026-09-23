<?php
// No backend logic required for this page
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Plant Diseases - 100 Unique Issues</title>

<style>
/* 🌿 GLOBAL */
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

body{
  background:linear-gradient(135deg,#f1f8e9,#ffffff);
  color:#222;
  line-height:1.6;
}

/* 🔴 HEADER */
header{
  background:linear-gradient(135deg,#c62828,#b71c1c);
  color:#fff;
  padding:30px 15px;
  text-align:center;
  box-shadow:0 6px 20px rgba(0,0,0,0.25);
}

header h1{
  font-size:30px;
  letter-spacing:0.5px;
}

/* 🟢 NAVBAR */
nav.main{
  background:linear-gradient(135deg,#2e7d32,#4CAF50);
  padding:12px;
  text-align:center;
  position:sticky;
  top:0;
  z-index:10;
}

nav.main a{
  color:#fff;
  margin:0 10px;
  text-decoration:none;
  font-weight:600;
  padding:6px 12px;
  border-radius:20px;
  transition:0.3s;
}

nav.main a:hover{
  background:rgba(255,255,255,0.2);
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
  text-decoration: none;
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
}
.profile-dropdown a:last-child { border-bottom: none; }
.profile-dropdown a:hover { background: #e8f5e9 !important; }
.profile-dropdown.open { display: block; }
@keyframes dropFade {
  from { opacity: 0; transform: translateY(-8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.profile-dropdown { animation: dropFade 0.18s ease; }

/* 🔍 SEARCH */
.search-container{
  text-align:center;
  margin:25px 0;
}

.search-container input{
  width:300px;
  padding:10px 14px;
  border-radius:30px;
  border:1px solid #ccc;
  font-size:15px;
  outline:none;
}

.search-container button{
  padding:10px 18px;
  border:none;
  border-radius:30px;
  background:linear-gradient(135deg,#2e7d32,#4CAF50);
  color:#fff;
  font-size:15px;
  cursor:pointer;
  margin-left:6px;
  box-shadow:0 6px 18px rgba(0,0,0,0.2);
  transition:0.3s;
}

.search-container button:hover{
  transform:translateY(-2px);
}

/* 📦 CONTENT */
.wrap{
  padding:40px 20px;
  max-width:1250px;
  margin:auto;
}

.wrap p{
  text-align:center;
  color:#555;
  margin-bottom:25px;
}

/* 🧩 GRID */
.disease-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:22px;
}

/* 🪴 CARD */
.card{
  background:rgba(255,255,255,0.95);
  border-radius:18px;
  padding:16px;
  box-shadow:0 15px 40px rgba(0,0,0,0.12);
  border-top:6px solid #c62828;
  transition:0.4s ease;
  position:relative;
  overflow:hidden;
}

.card::before{
  content:"";
  position:absolute;
  inset:0;
  background:linear-gradient(120deg,transparent,rgba(76,175,80,0.08),transparent);
  opacity:0;
  transition:0.4s;
}

.card:hover::before{
  opacity:1;
}

.card:hover{
  transform:translateY(-10px) scale(1.02);
  box-shadow:0 25px 70px rgba(0,0,0,0.18);
}

/* 🖼 IMAGE */
.card img{
  width:100%;
  height:160px;
  object-fit:cover;
  border-radius:12px;
}

/* 📝 TEXT */
.card h3{
  margin:12px 0 6px;
  font-size:18px;
  color:#c62828;
}

.card p{
  font-size:13.5px;
  margin:4px 0;
  color:#333;
}

/* 🔻 FOOTER */
footer{
  margin-top:40px;
  background:#fceaea;
  padding:16px;
  text-align:center;
  color:#555;
  font-size:14px;
}

/* 📱 RESPONSIVE */
@media(max-width:600px){
  header h1{font-size:22px}
  .search-container input{width:90%}
}
</style>
</head>
<?php include 'global_style.php'; ?>
<body>

<header>
  <h1>Plant Diseases & Issues </h1>
</header>

<nav class="main" style="position: relative;">
  <a href="index.php">🏠 Home</a>
  <a href="Flower_Plants_Shop.php">🌸 Flower Plants</a>
  <a href="Fruit_Plants_Shop.php">🍋 Fruit Plants</a>
  <a href="Medicinal_Plants_Shop.php">🌿 Medicinal Plants</a>
  <a href="air_purification_plants_detailed.php">💨 AirPurify Plants</a>
  <a href="plant_diseases_100.php">🦠 Diseases</a>
  <a href="contact.php">💬 Contact</a>
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

<div class="search-container">
  <form action="SearchDisease.php" method="get">
      <input type="text" name="query" placeholder="Search Plants Diseases" required>
      <button type="submit">Search Plants</button>
  </form>
</div>

<div class="wrap">
  <!-- <p style="text-align:center; color:#555;">
    Each card shows Cause, Symptoms and Treatment. Images from <code>Images/diseases/</code>.
  </p> -->

  <div class="disease-grid">

        <!-- 1 -->
    <div class="card">
      <img src="Images/diseases/leaf_yellowing.jpeg" alt="Leaf Yellowing">
      <h3>1. Leaf Yellowing (Chlorosis)</h3>
      <p><strong>Cause:</strong> Iron deficiency or poor drainage.</p>
      <p><strong>Symptoms:</strong> New leaves pale, veins may stay green.</p>
      <p><strong>Treatment:</strong> Test soil, apply chelated iron or correct pH.</p>
    </div>

    <!-- 2 -->
    <div class="card">
      <img src="Images/diseases/bacterial_wilt.jpeg" alt="Bacterial Wilt">
      <h3>2. Bacterial Wilt</h3>
      <p><strong>Cause:</strong> Soil-borne bacteria (e.g., Ralstonia).</p>
      <p><strong>Symptoms:</strong> Sudden wilting, stem browning.</p>
      <p><strong>Treatment:</strong> Remove infected plants; improve crop rotation.</p>
    </div>

    <!-- 3 -->
    <div class="card">
      <img src="Images/diseases/aphid_infestation.jpeg" alt="Aphid Infestation">
      <h3>3. Aphid Infestation</h3>
      <p><strong>Cause:</strong> Sap-feeding insects.</p>
      <p><strong>Symptoms:</strong> Sticky honeydew, curled leaves.</p>
      <p><strong>Treatment:</strong> Spray insecticidal soap or neem oil; encourage predators.</p>
    </div>

    <!-- 4 -->
    <div class="card">
      <img src="Images/diseases/root_rot.jpeg" alt="Root Rot">
      <h3>4. Root Rot (Phytophthora)</h3>
      <p><strong>Cause:</strong> Waterlogged soil & fungal pathogen.</p>
      <p><strong>Symptoms:</strong> Soft brown roots, stunting, wilting.</p>
      <p><strong>Treatment:</strong> Improve drainage, repot into fresh, well-drained mix.</p>
    </div>

    <!-- 5 -->
    <div class="card">
      <img src="Images/diseases/early_blight.jpeg" alt="Early Blight">
      <h3>5. Early Blight</h3>
      <p><strong>Cause:</strong> Fungal infection (Alternaria).</p>
      <p><strong>Symptoms:</strong> Circular brown lesions on leaves and stems.</p>
      <p><strong>Treatment:</strong> Remove infected foliage; apply appropriate fungicide.</p>
    </div>

    <!-- 6 -->
    <div class="card">
      <img src="Images/diseases/rust.jpeg" alt="Rust">
      <h3>6. Rust</h3>
      <p><strong>Cause:</strong> Rust fungi (Pucciniales).</p>
      <p><strong>Symptoms:</strong> Orange/brown pustules underside of leaves.</p>
      <p><strong>Treatment:</strong> Remove affected leaves; fungicide, increase air flow.</p>
    </div>

    <!-- 7 -->
    <div class="card">
      <img src="Images/diseases/leaf_spot.jpeg" alt="Leaf Spot">
      <h3>7. Leaf Spot</h3>
      <p><strong>Cause:</strong> Various fungal or bacterial pathogens.</p>
      <p><strong>Symptoms:</strong> Brown/black spots on foliage.</p>
      <p><strong>Treatment:</strong> Sanitation; fungicide or bactericide as appropriate.</p>
    </div>

    <!-- 8 -->
    <div class="card">
      <img src="Images/diseases/downy_mildew.jpeg" alt="Downy Mildew">
      <h3>8. Downy Mildew</h3>
      <p><strong>Cause:</strong> Oomycetes in humid conditions.</p>
      <p><strong>Symptoms:</strong> Pale patches, gray/white down on underside.</p>
      <p><strong>Treatment:</strong> Reduce humidity, apply copper fungicide.</p>
    </div>

    <!-- 9 -->
    <div class="card">
      <img src="Images/diseases/spider_mites.jpeg" alt="Spider Mites">
      <h3>9. Spider Mites</h3>
      <p><strong>Cause:</strong> Tiny mite pests, dry conditions.</p>
      <p><strong>Symptoms:</strong> Fine webbing, stippled leaves.</p>
      <p><strong>Treatment:</strong> Increase humidity, use miticide or neem oil.</p>
    </div>

    <!-- 10 -->
    <div class="card">
      <img src="Images/diseases/powdery_mildew.jpeg" alt="Powdery Mildew">
      <h3>10. Powdery Mildew</h3>
      <p><strong>Cause:</strong> Powdery fungi on foliage.</p>
      <p><strong>Symptoms:</strong> White powdery coating on leaves/stems.</p>
      <p><strong>Treatment:</strong> Prune for airflow; use sulfur or potassium bicarbonate spray.</p>
    </div>

    <!-- 11 -->
    <div class="card">
      <img src="Images/diseases/mosaic_virus.jpeg" alt="Mosaic Virus">
      <h3>11. Mosaic Virus</h3>
      <p><strong>Cause:</strong> Viral pathogens spread by insects.</p>
      <p><strong>Symptoms:</strong> Mottled or mosaic leaf pattern, stunting.</p>
      <p><strong>Treatment:</strong> Remove infected plants; control insect vectors.</p>
    </div>

    <!-- 12 -->
    <div class="card">
      <img src="Images/diseases/scale_insects.jpeg" alt="Scale Insects">
      <h3>12. Scale Insects</h3>
      <p><strong>Cause:</strong> Sap-sucking scale pests.</p>
      <p><strong>Symptoms:</strong> Bumps on stems/leaves; honeydew and sooty mold.</p>
      <p><strong>Treatment:</strong> Scrape off scales; use horticultural oil.</p>
    </div>

    <!-- 13 -->
    <div class="card">
      <img src="Images/diseases/mealybugs.jpeg" alt="Mealybugs">
      <h3>13. Mealybugs</h3>
      <p><strong>Cause:</strong> Soft-bodied sap feeders.</p>
      <p><strong>Symptoms:</strong> White cottony masses; plant decline.</p>
      <p><strong>Treatment:</strong> Remove by hand or with alcohol swab; insecticidal soap.</p>
    </div>

    <!-- 14 -->
    <div class="card">
      <img src="Images/diseases/thrips.jpeg" alt="Thrips">
      <h3>14. Thrips</h3>
      <p><strong>Cause:</strong> Tiny piercing-sucking insects.</p>
      <p><strong>Symptoms:</strong> Silvering of leaves, distorted flowers.</p>
      <p><strong>Treatment:</strong> Sticky traps, insecticidal soap or spinosad.</p>
    </div>

    <!-- 15 -->
    <div class="card">
      <img src="Images/diseases/whitefly_infestation.jpeg" alt="Whitefly">
      <h3>15. Whitefly Infestation</h3>
      <p><strong>Cause:</strong> Small flying sap-feeders.</p>
      <p><strong>Symptoms:</strong> Yellowing leaves, sticky honeydew.</p>
      <p><strong>Treatment:</strong> Yellow sticky traps; insecticidal soap.</p>
    </div>

    <!-- 16 -->
    <div class="card">
      <img src="Images/diseases/botrytis_gray_mold.jpeg" alt="Botrytis (Gray Mold)">
      <h3>16. Botrytis (Gray Mold)</h3>
      <p><strong>Cause:</strong> Botrytis cinerea fungus in damp conditions.</p>
      <p><strong>Symptoms:</strong> Gray fuzzy mold on flowers/leaves.</p>
      <p><strong>Treatment:</strong> Remove affected tissue, improve airflow, fungicide.</p>
    </div>

    <!-- 17 -->
    <div class="card">
      <img src="Images/diseases/canker.jpeg" alt="Canker">
      <h3>17. Canker</h3>
      <p><strong>Cause:</strong> Fungal or bacterial stem infection.</p>
      <p><strong>Symptoms:</strong> Sunken, dead areas on bark/stems.</p>
      <p><strong>Treatment:</strong> Prune out infected wood; disinfect tools.</p>
    </div>

    <!-- 18 -->
    <div class="card">
      <img src="Images/diseases/crown_gall.jpeg" alt="Crown Gall">
      <h3>18. Crown Gall</h3>
      <p><strong>Cause:</strong> Agrobacterium tumefaciens bacteria.</p>
      <p><strong>Symptoms:</strong> Tumor-like galls at crown or roots.</p>
      <p><strong>Treatment:</strong> Remove and destroy infected plants; avoid contaminated soil.</p>
    </div>

    <!-- 19 -->
    <div class="card">
      <img src="Images/diseases/verticillium_wilt.jpeg" alt="Verticillium Wilt">
      <h3>19. Verticillium Wilt</h3>
      <p><strong>Cause:</strong> Soil fungus (Verticillium).</p>
      <p><strong>Symptoms:</strong> Yellowing, wilting, vascular browning.</p>
      <p><strong>Treatment:</strong> Remove infected plants; rotate crops.</p>
    </div>

    <!-- 20 -->
    <div class="card">
      <img src="Images/diseases/fusarium_wilt.jpeg" alt="Fusarium Wilt">
      <h3>20. Fusarium Wilt</h3>
      <p><strong>Cause:</strong> Fusarium oxysporum fungus.</p>
      <p><strong>Symptoms:</strong> One-sided wilting, brown vascular tissues.</p>
      <p><strong>Treatment:</strong> Use resistant varieties; soil solarization where possible.</p>
    </div>

    <!-- 21 -->
    <div class="card">
      <img src="Images/diseases/brown_patch.jpeg" alt="Brown Patch">
      <h3>21. Brown Patch (Turf)</h3>
      <p><strong>Cause:</strong> Fungal disease in warm, wet weather.</p>
      <p><strong>Symptoms:</strong> Circular brown patches in turf.</p>
      <p><strong>Treatment:</strong> Improve drainage; fungicide and cultural control.</p>
    </div>

    <!-- 22 -->
    <div class="card">
      <img src="Images/diseases/black_spot.jpeg" alt="Black Spot">
      <h3>22. Black Spot (Rose)</h3>
      <p><strong>Cause:</strong> Diplocarpon rosae fungus.</p>
      <p><strong>Symptoms:</strong> Black circular spots on rose leaves.</p>
      <p><strong>Treatment:</strong> Remove leaves; use fungicide sprays.</p>
    </div>

    <!-- 23 -->
    <div class="card">
      <img src="Images/diseases/blossom_end_rot.jpeg" alt="Blossom End Rot">
      <h3>23. Blossom End Rot</h3>
      <p><strong>Cause:</strong> Calcium deficiency and irregular watering.</p>
      <p><strong>Symptoms:</strong> Dark sunken area at fruit bottom.</p>
      <p><strong>Treatment:</strong> Consistent watering; supply calcium if needed.</p>
    </div>

    <!-- 24 -->
    <div class="card">
      <img src="Images/diseases/sunscald_sunburn.jpeg" alt="Sunscald / Sunburn">
      <h3>24. Sunscald / Sunburn</h3>
      <p><strong>Cause:</strong> Sudden exposure to intense sun/heat.</p>
      <p><strong>Symptoms:</strong> Bleached or brown patches on leaves/fruit.</p>
      <p><strong>Treatment:</strong> Provide shade; gradual acclimation.</p>
    </div>

    <!-- 25 -->
    <div class="card">
      <img src="Images/diseases/leaf_curl.jpeg" alt="Leaf Curl">
      <h3>25. Leaf Curl</h3>
      <p><strong>Cause:</strong> Fungal pathogens or environmental stress.</p>
      <p><strong>Symptoms:</strong> Twisted, puckered leaves.</p>
      <p><strong>Treatment:</strong> Prune affected parts; fungicide or cultural fixes.</p>
    </div>

    <!-- 26 -->
    <div class="card">
      <img src="Images/diseases/leaf_scorch.jpeg" alt="Leaf Scorch">
      <h3>26. Leaf Scorch</h3>
      <p><strong>Cause:</strong> High heat, drought or root damage.</p>
      <p><strong>Symptoms:</strong> Margins turn brown and crispy.</p>
      <p><strong>Treatment:</strong> Improve watering and mulch roots.</p>
    </div>

    <!-- 27 -->
    <div class="card">
      <img src="Images/diseases/iron_chlorosis.jpeg" alt="Iron Chlorosis">
      <h3>27. Iron Chlorosis</h3>
      <p><strong>Cause:</strong> Iron unavailable due to high pH.</p>
      <p><strong>Symptoms:</strong> Yellow leaves with green veins.</p>
      <p><strong>Treatment:</strong> Lower pH or apply iron chelate.</p>
    </div>

    <!-- 28 -->
    <div class="card">
      <img src="Images/diseases/root_knot_nematode.jpeg" alt="Root-knot Nematode">
      <h3>28. Root-knot Nematode</h3>
      <p><strong>Cause:</strong> Soil nematodes attacking roots.</p>
      <p><strong>Symptoms:</strong> Galls on roots, poor vigor.</p>
      <p><strong>Treatment:</strong> Solarize soil; use resistant varieties.</p>
    </div>

    <!-- 29 -->
    <div class="card">
      <img src="Images/diseases/leafminer_damage.jpeg" alt="Leafminer">
      <h3>29. Leafminer Damage</h3>
      <p><strong>Cause:</strong> Larvae tunneling inside leaves.</p>
      <p><strong>Symptoms:</strong> White squiggly tunnels in leaves.</p>
      <p><strong>Treatment:</strong> Remove affected leaves; use systemic insecticide if severe.</p>
    </div>

    <!-- 30 -->
    <div class="card">
      <img src="Images/diseases/caterpillar_feeding.jpeg" alt="Caterpillar Feeding">
      <h3>30. Caterpillar Feeding</h3>
      <p><strong>Cause:</strong> Caterpillar larvae chewing foliage.</p>
      <p><strong>Symptoms:</strong> Ragged holes, defoliation.</p>
      <p><strong>Treatment:</strong> Handpick; use Bacillus thuringiensis (Bt) if needed.</p>
    </div>

    <!-- 31 -->
    <div class="card">
      <img src="Images/diseases/slugs_and_snails.jpeg" alt="Slugs and Snails">
      <h3>31. Slugs & Snails</h3>
      <p><strong>Cause:</strong> Mollusc pests in moist conditions.</p>
      <p><strong>Symptoms:</strong> Irregular holes, slime trails at night.</p>
      <p><strong>Treatment:</strong> Handpick at night; traps or iron phosphate baits.</p>
    </div>

    <!-- 32 -->
    <div class="card">
      <img src="Images/diseases/borer_infestation.jpeg" alt="Borer Infestation">
      <h3>32. Borer Infestation</h3>
      <p><strong>Cause:</strong> Beetle/larvae tunneling in wood.</p>
      <p><strong>Symptoms:</strong> Sawdust, dieback, holes in bark.</p>
      <p><strong>Treatment:</strong> Remove infected branches; control adults with traps.</p>
    </div>

    <!-- 33 -->
    <div class="card">
      <img src="Images/diseases/weevil_damage.jpeg" alt="Weevil Damage">
      <h3>33. Weevil Damage</h3>
      <p><strong>Cause:</strong> Root-and-leaf feeding beetles.</p>
      <p><strong>Symptoms:</strong> Notched leaf margins, root damage.</p>
      <p><strong>Treatment:</strong> Apply appropriate insecticide; remove affected plants.</p>
    </div>

    <!-- 34 -->
    <div class="card">
      <img src="Images/diseases/tomato_yellow_leaf_curl_virus.jpeg" alt="Tomato Yellow Leaf Curl">
      <h3>34. Tomato Yellow Leaf Curl Virus</h3>
      <p><strong>Cause:</strong> Whitefly-transmitted virus.</p>
      <p><strong>Symptoms:</strong> Leaf curling, yellowing, reduced yield.</p>
      <p><strong>Treatment:</strong> Remove infected plants; control whiteflies.</p>
    </div>

    <!-- 35 -->
    <div class="card">
      <img src="Images/diseases/cercospora_leaf_spot.jpeg" alt="Cercospora Leaf Spot">
      <h3>35. Cercospora Leaf Spot</h3>
      <p><strong>Cause:</strong> Cercospora fungus.</p>
      <p><strong>Symptoms:</strong> Small circular spots with tan centers.</p>
      <p><strong>Treatment:</strong> Fungicide and sanitation.</p>
    </div>

    <!-- 36 -->
    <div class="card">
      <img src="Images/diseases/clubroot.jpeg" alt="Clubroot">
      <h3>36. Clubroot</h3>
      <p><strong>Cause:</strong> Plasmodiophora brassicae in brassicas.</p>
      <p><strong>Symptoms:</strong> Swollen distorted roots, wilting.</p>
      <p><strong>Treatment:</strong> Raise soil pH, rotate crops.</p>
    </div>

    <!-- 37 -->
    <div class="card">
      <img src="Images/diseases/damping_off.jpeg" alt="Damping Off">
      <h3>37. Damping Off</h3>
      <p><strong>Cause:</strong> Soil-borne fungi attacking seedlings.</p>
      <p><strong>Symptoms:</strong> Seedlings collapse at soil line.</p>
      <p><strong>Treatment:</strong> Use sterile medium and proper moisture; fungicide seed treatment.</p>
    </div>

    <!-- 38 -->
    <div class="card">
      <img src="Images/diseases/bacterial_leaf_spot.jpeg" alt="Bacterial Leaf Spot">
      <h3>38. Bacterial Leaf Spot</h3>
      <p><strong>Cause:</strong> Bacterial pathogens (e.g., Xanthomonas).</p>
      <p><strong>Symptoms:</strong> Water-soaked spots that turn brown.</p>
      <p><strong>Treatment:</strong> Copper sprays; remove infected tissue.</p>
    </div>

    <!-- 39 -->
    <div class="card">
      <img src="Images/diseases/fire_blight.jpeg" alt="Fire Blight">
      <h3>39. Fire Blight</h3>
      <p><strong>Cause:</strong> Erwinia amylovora bacteria on pome fruits.</p>
      <p><strong>Symptoms:</strong> Blackened shoots, oozing cankers.</p>
      <p><strong>Treatment:</strong> Prune infected wood; disinfect tools between cuts.</p>
    </div>

    <!-- 40 -->
    <div class="card">
      <img src="Images/diseases/needle_cast.jpeg" alt="Needle Cast">
      <h3>40. Needle Cast</h3>
      <p><strong>Cause:</strong> Fungal infection of conifers.</p>
      <p><strong>Symptoms:</strong> Browning/losing needles prematurely.</p>
      <p><strong>Treatment:</strong> Remove infected needles; fungicide on susceptible species.</p>
    </div>

    <!-- 41 -->
    <div class="card">
      <img src="Images/diseases/tip_blight.jpeg" alt="Tip Blight">
      <h3>41. Tip Blight</h3>
      <p><strong>Cause:</strong> Fungal pathogens on shoots.</p>
      <p><strong>Symptoms:</strong> Dead shoot tips and dieback.</p>
      <p><strong>Treatment:</strong> Prune back to healthy tissue; fungicide if necessary.</p>
    </div>

    <!-- 42 -->
    <div class="card">
      <img src="Images/diseases/scab_potato_tomato.jpeg" alt="Scab">
      <h3>42. Scab (Potato/Tomato)</h3>
      <p><strong>Cause:</strong> Streptomyces or fungi depending on crop.</p>
      <p><strong>Symptoms:</strong> Rough lesions on tubers or fruit.</p>
      <p><strong>Treatment:</strong> Crop rotation; resistant varieties.</p>
    </div>

    <!-- 43 -->
    <div class="card">
      <img src="Images/diseases/leaf_blister.jpeg" alt="Leaf Blister">
      <h3>43. Leaf Blister</h3>
      <p><strong>Cause:</strong> Fungal infection (e.g., Taphrina).</p>
      <p><strong>Symptoms:</strong> Blistered, puckered leaves.</p>
      <p><strong>Treatment:</strong> Remove affected leaves; fungicide if required.</p>
    </div>

    <!-- 44 -->
    <div class="card">
      <img src="Images/diseases/sooty_mold.jpeg" alt="Sooty Mold">
      <h3>44. Sooty Mold</h3>
      <p><strong>Cause:</strong> Fungal growth on honeydew from pests.</p>
      <p><strong>Symptoms:</strong> Black soot-like coating on leaves.</p>
      <p><strong>Treatment:</strong> Control sap-sucking insects; wash surfaces.</p>
    </div>

    <!-- 45 -->
    <div class="card">
      <img src="Images/diseases/phytophthora_root_rot.jpeg" alt="Phytophthora Root Rot">
      <h3>45. Phytophthora Root Rot</h3>
      <p><strong>Cause:</strong> Water mold pathogen in wet soils.</p>
      <p><strong>Symptoms:</strong> Collapsed seedlings, root decay.</p>
      <p><strong>Treatment:</strong> Improve drainage; fungicide drenches for high-value plants.</p>
    </div>

    <!-- 46 -->
    <div class="card">
      <img src="Images/diseases/clubroot_brassicas.jpeg" alt="Clubroot (Brassicas)">
      <h3>46. Clubroot (Brassicas)</h3>
      <p><strong>Cause:</strong> Soil protozoan pathogen.</p>
      <p><strong>Symptoms:</strong> Swollen roots; wilting and stunting.</p>
      <p><strong>Treatment:</strong> Raise pH and rotate away from brassicas.</p>
    </div>

    <!-- 47 -->
    <div class="card">
      <img src="Images/diseases/powdery_scab.jpeg" alt="Powdery Scab">
      <h3>47. Powdery Scab</h3>
      <p><strong>Cause:</strong> Spongospora subterranea on potatoes.</p>
      <p><strong>Symptoms:</strong> Pustules on tubers.</p>
      <p><strong>Treatment:</strong> Use certified seed; rotate fields.</p>
    </div>

    <!-- 48 -->
    <div class="card">
      <img src="Images/diseases/yellow_mosaic.jpeg" alt="Yellow Mosaic">
      <h3>48. Yellow Mosaic</h3>
      <p><strong>Cause:</strong> Viral infection transmitted by aphids.</p>
      <p><strong>Symptoms:</strong> Yellow mosaic patterns on leaves.</p>
      <p><strong>Treatment:</strong> Remove inoculum; control vectors.</p>
    </div>

    <!-- 49 -->
    <div class="card">
      <img src="Images/diseases/herbicide_injury.jpeg" alt="Herbicide Injury">
      <h3>49. Herbicide Injury</h3>
      <p><strong>Cause:</strong> Drift or residual herbicide exposure.</p>
      <p><strong>Symptoms:</strong> Twisted growth, chlorosis, necrosis.</p>
      <p><strong>Treatment:</strong> Avoid exposure; flush soil if possible; replace plants if severe.</p>
    </div>

    <!-- 50 -->
    <div class="card">
      <img src="Images/diseases/drought_water_stress.jpeg" alt="Drought Stress">
      <h3>50. Drought / Water Stress</h3>
      <p><strong>Cause:</strong> Inadequate soil moisture.</p>
      <p><strong>Symptoms:</strong> Wilting, leaf curling, browning.</p>
      <p><strong>Treatment:</strong> Deep watering, mulching, choose drought-tolerant species.</p>
    </div>

    <!-- 51 -->
    <div class="card">
      <img src="Images/diseases/frost_damage.jpeg" alt="Frost Damage">
      <h3>51. Frost Damage</h3>
      <p><strong>Cause:</strong> Low temperature exposure.</p>
      <p><strong>Symptoms:</strong> Blackened, water-soaked tissue.</p>
      <p><strong>Treatment:</strong> Protect with covers; prune dead tissue after thaw.</p>
    </div>

    <!-- 52 -->
    <div class="card">
      <img src="Images/diseases/salt_salinity_damage.jpeg" alt="Salt Damage">
      <h3>52. Salt / Salinity Damage</h3>
      <p><strong>Cause:</strong> Salt buildup from irrigation or roads.</p>
      <p><strong>Symptoms:</strong> Leaf margin burn, stunted growth.</p>
      <p><strong>Treatment:</strong> Leach soil with fresh water; improve drainage.</p>
    </div>

    <!-- 53 -->
    <div class="card">
      <img src="Images/diseases/heat_stress.jpeg" alt="Heat Stress">
      <h3>53. Heat Stress</h3>
      <p><strong>Cause:</strong> Prolonged high temperatures.</p>
      <p><strong>Symptoms:</strong> Leaf drop, scorched foliage.</p>
      <p><strong>Treatment:</strong> Shade cloth, mulching, extra irrigation.</p>
    </div>

    <!-- 54 -->
    <div class="card">
      <img src="Images/diseases/root_damage_mechanical.jpeg" alt="Root Damage (Mechanical)">
      <h3>54. Root Damage (Mechanical)</h3>
      <p><strong>Cause:</strong> Construction, compaction or transplant injury.</p>
      <p><strong>Symptoms:</strong> Poor vigor, wilting despite adequate water.</p>
      <p><strong>Treatment:</strong> Reduce compaction; prune broken roots; improve soil health.</p>
    </div>

    <!-- 55 -->
    <div class="card">
      <img src="Images/diseases/nitrogen_deficiency.jpeg" alt="Nitrogen Deficiency">
      <h3>55. Nitrogen Deficiency</h3>
      <p><strong>Cause:</strong> Low available nitrogen.</p>
      <p><strong>Symptoms:</strong> Uniform yellowing starting with older leaves.</p>
      <p><strong>Treatment:</strong> Apply balanced nitrogen fertilizer or compost.</p>
    </div>

    <!-- 56 -->
    <div class="card">
      <img src="Images/diseases/potassium_deficiency.jpeg" alt="Potassium Deficiency">
      <h3>56. Potassium Deficiency</h3>
      <p><strong>Cause:</strong> Low soil potassium.</p>
      <p><strong>Symptoms:</strong> Marginal scorch and weak stems.</p>
      <p><strong>Treatment:</strong> Potash application; wood ash (limited) or K fertilizer.</p>
    </div>

    <!-- 57 -->
    <div class="card">
      <img src="Images/diseases/magnesium_deficiency.jpeg" alt="Magnesium Deficiency">
      <h3>57. Magnesium Deficiency</h3>
      <p><strong>Cause:</strong> Low soil magnesium.</p>
      <p><strong>Symptoms:</strong> Interveinal yellowing on older leaves.</p>
      <p><strong>Treatment:</strong> Epsom salts foliar feed or soil application.</p>
    </div>

    <!-- 58 -->
    <div class="card">
      <img src="Images/diseases/zinc_deficiency.jpeg" alt="Zinc Deficiency">
      <h3>58. Zinc Deficiency</h3>
      <p><strong>Cause:</strong> Zinc-poor soils or high pH.</p>
      <p><strong>Symptoms:</strong> Small leaves, shortened internodes.</p>
      <p><strong>Treatment:</strong> Zinc sulfate or chelated zinc application.</p>
    </div>

    <!-- 59 -->
    <div class="card">
      <img src="Images/diseases/boron_deficiency.jpeg" alt="Boron Deficiency">
      <h3>59. Boron Deficiency</h3>
      <p><strong>Cause:</strong> Low boron availability.</p>
      <p><strong>Symptoms:</strong> Growing points dieback; hollow stems/fruit.</p>
      <p><strong>Treatment:</strong> Apply borate carefully (narrow range of safety).</p>
    </div>

    <!-- 60 -->
    <div class="card">
      <img src="Images/diseases/calcium_deficiency.jpeg" alt="Calcium Deficiency">
      <h3>60. Calcium Deficiency</h3>
      <p><strong>Cause:</strong> Poor uptake due to irregular watering.</p>
      <p><strong>Symptoms:</strong> Blossom end rot, distorted new growth.</p>
      <p><strong>Treatment:</strong> Even watering; calcium amendments if needed.</p>
    </div>

    <!-- 61 -->
    <div class="card">
      <img src="Images/diseases/shot_hole_borer_wood_borers.jpeg" alt="Shot Hole Borer">
      <h3>61. Shot Hole Borer / Wood Borers</h3>
      <p><strong>Cause:</strong> Beetles boring into stems/trunks.</p>
      <p><strong>Symptoms:</strong> Holes, frass, branch dieback.</p>
      <p><strong>Treatment:</strong> Remove and destroy infested wood; insecticide for adults.</p>
    </div>

    <!-- 62 -->
    <div class="card">
      <img src="Images/diseases/downy_mildew_cucurbits.jpeg" alt="Downy on Cucurbits">
      <h3>62. Downy Mildew (Cucurbits)</h3>
      <p><strong>Cause:</strong> Specialized downy mildew pathogens.</p>
      <p><strong>Symptoms:</strong> Angular yellow patches on leaves.</p>
      <p><strong>Treatment:</strong> Use resistant cultivars; fungicide sprays.</p>
    </div>

    <!-- 63 -->
    <div class="card">
      <img src="Images/diseases/late_blight.jpeg" alt="Late Blight">
      <h3>63. Late Blight</h3>
      <p><strong>Cause:</strong> Phytophthora infestans on potato/tomato.</p>
      <p><strong>Symptoms:</strong> Water-soaked lesions; rapid collapse.</p>
      <p><strong>Treatment:</strong> Destroy infected plants; regular fungicide protection.</p>
    </div>

    <!-- 64 -->
    <div class="card">
      <img src="Images/diseases/early_blight_tomato.jpeg" alt="Early Blight on Tomato">
      <h3>64. Early Blight (Tomato)</h3>
      <p><strong>Cause:</strong> Alternaria solani.</p>
      <p><strong>Symptoms:</strong> Target-like spots on leaves; defoliation.</p>
      <p><strong>Treatment:</strong> Mulch, remove debris; fungicides if necessary.</p>
    </div>

    <!-- 65 -->
    <div class="card">
      <img src="Images/diseases/powdery_mildew_grapes.jpeg" alt="Powdery Mildew on Grapes">
      <h3>65. Powdery Mildew (Grapes)</h3>
      <p><strong>Cause:</strong> Erysiphe necator fungus.</p>
      <p><strong>Symptoms:</strong> White dusty coating on leaves and fruit.</p>
      <p><strong>Treatment:</strong> Sulfur or specific fungicides; canopy management.</p>
    </div>

    <!-- 66 -->
    <div class="card">
      <img src="Images/diseases/pear_rust.jpeg" alt="Pear Rust">
      <h3>66. Pear Rust</h3>
      <p><strong>Cause:</strong> Gymnosporangium fungi.</p>
      <p><strong>Symptoms:</strong> Bright orange spots on leaves; deformities.</p>
      <p><strong>Treatment:</strong> Remove alternate juniper hosts; fungicide.</p>
    </div>

    <!-- 67 -->
    <div class="card">
      <img src="Images/diseases/peacock_spot_olive.jpeg" alt="Peacock Spot">
      <h3>67. Peacock Spot (Olive)</h3>
      <p><strong>Cause:</strong> Fungal infection on olive leaves.</p>
      <p><strong>Symptoms:</strong> Dark round spots with yellow halo.</p>
      <p><strong>Treatment:</strong> Copper sprays and sanitation.</p>
    </div>

    <!-- 68 -->
    <div class="card">
      <img src="Images/diseases/black_sigatoka_banana.jpeg" alt="Black Sigatoka">
      <h3>68. Black Sigatoka (Banana)</h3>
      <p><strong>Cause:</strong> Mycosphaerella fijiensis.</p>
      <p><strong>Symptoms:</strong> Dark streaks and leaf necrosis.</p>
      <p><strong>Treatment:</strong> Regular fungicide applications; resistant clones.</p>
    </div>

    <!-- 69 -->
    <div class="card">
      <img src="Images/diseases/apple_scab.jpeg" alt="Apple Scab">
      <h3>69. Apple Scab</h3>
      <p><strong>Cause:</strong> Venturia inaequalis fungus.</p>
      <p><strong>Symptoms:</strong> Olive-green spots on leaves and fruit.</p>
      <p><strong>Treatment:</strong> Scab-resistant varieties; fungicides in spring.</p>
    </div>

    <!-- 70 -->
    <div class="card">
      <img src="Images/diseases/vascular_wilt_general.jpeg" alt="Vascular Wilt / General">
      <h3>70. Vascular Wilt (General)</h3>
      <p><strong>Cause:</strong> Several fungi blocking xylem.</p>
      <p><strong>Symptoms:</strong> Wilting, brown streaks in wood.</p>
      <p><strong>Treatment:</strong> Remove infected material; use resistant species where possible.</p>
    </div>

    <!-- 71 -->
    <div class="card">
      <img src="Images/diseases/anthracnose.jpeg" alt="Anthracnose">
      <h3>71. Anthracnose</h3>
      <p><strong>Cause:</strong> Colletotrichum and similar fungi.</p>
      <p><strong>Symptoms:</strong> Dark sunken lesions on leaves, stems and fruit.</p>
      <p><strong>Treatment:</strong> Sanitation; fungicide and improved airflow.</p>
    </div>

    <!-- 72 -->
    <div class="card">
      <img src="Images/diseases/botrytis_on_flowers.jpeg" alt="Botrytis on Flowers">
      <h3>72. Botrytis on Flowers</h3>
      <p><strong>Cause:</strong> Botrytis in humid environments.</p>
      <p><strong>Symptoms:</strong> Gray mold on blooms and buds.</p>
      <p><strong>Treatment:</strong> Remove affected blooms and improve ventilation.</p>
    </div>

    <!-- 73 -->
    <div class="card">
      <img src="Images/diseases/sclerotinia_white_mold.jpeg" alt="Sclerotinia (White Mold)">
      <h3>73. Sclerotinia (White Mold)</h3>
      <p><strong>Cause:</strong> Sclerotinia sclerotiorum.</p>
      <p><strong>Symptoms:</strong> Watery soft rot, white mycelium, black sclerotia.</p>
      <p><strong>Treatment:</strong> Remove infected tissue; fungicide; crop rotation.</p>
    </div>

    <!-- 74 -->
    <div class="card">
      <img src="Images/diseases/crown_rot.jpeg" alt="Crown Rot">
      <h3>74. Crown Rot</h3>
      <p><strong>Cause:</strong> Fungal pathogens at crown area.</p>
      <p><strong>Symptoms:</strong> Crown decay, collapse of plant.</p>
      <p><strong>Treatment:</strong> Improve soil drainage; avoid injuring crown.</p>
    </div>

    <!-- 75 -->
    <div class="card">
      <img src="Images/diseases/phoma_leaf_spot.jpeg" alt="Phoma Leaf Spot">
      <h3>75. Phoma Leaf Spot</h3>
      <p><strong>Cause:</strong> Phoma species fungi.</p>
      <p><strong>Symptoms:</strong> Small dark lesions often grouped.</p>
      <p><strong>Treatment:</strong> Remove debris; fungicide if outbreak occurs.</p>
    </div>

    <!-- 76 -->
    <div class="card">
      <img src="Images/diseases/banana_bunchy_top_virus.jpeg" alt="Banana Bunchy Top Virus">
      <h3>76. Banana Bunchy Top Virus</h3>
      <p><strong>Cause:</strong> Viral disease transmitted by aphids.</p>
      <p><strong>Symptoms:</strong> Stunted growth; bunching of leaves.</p>
      <p><strong>Treatment:</strong> Remove infected plants; control vectors.</p>
    </div>

    <!-- 77 -->
    <div class="card">
      <img src="Images/diseases/brown_blotch.jpeg" alt="Brown Blotch">
      <h3>77. Brown Blotch</h3>
      <p><strong>Cause:</strong> Bacterial or fungal agents on foliage/fruit.</p>
      <p><strong>Symptoms:</strong> Brown spots that may coalesce.</p>
      <p><strong>Treatment:</strong> Improve spray coverage; remove infected parts.</p>
    </div>

    <!-- 78 -->
    <div class="card">
      <img src="Images/diseases/vine_weevil_adult_grub_damage.jpeg" alt="Vine Weevil">
      <h3>78. Vine Weevil (Adult/Grub Damage)</h3>
      <p><strong>Cause:</strong> Weevil larvae feeding on roots.</p>
      <p><strong>Symptoms:</strong> Wilting, plant collapse; adult notches on leaves.</p>
      <p><strong>Treatment:</strong> Apply nematodes for larvae; pick adults at night.</p>
    </div>

    <!-- 79 -->
    <div class="card">
      <img src="Images/diseases/armored_scale_infestation.jpeg" alt="Armored Scale">
      <h3>79. Armored Scale Infestation</h3>
      <p><strong>Cause:</strong> Armored scale insects attaching to bark/leaves.</p>
      <p><strong>Symptoms:</strong> Tiny hard bumps; yellowing leaves.</p>
      <p><strong>Treatment:</strong> Horticultural oil during dormant season; manual removal.</p>
    </div>

    <!-- 80 -->
    <div class="card">
      <img src="Images/diseases/vegetable_downy_mildew.jpeg" alt="Vegetable Downy Mildew">
      <h3>80. Vegetable Downy Mildew</h3>
      <p><strong>Cause:</strong> Oomycetes on vegetable leaves.</p>
      <p><strong>Symptoms:</strong> Pale angular spots on topside; downy growth underside.</p>
      <p><strong>Treatment:</strong> Use resistant varieties; fungicide program.</p>
    </div>

    <!-- 81 -->
    <div class="card">
      <img src="Images/diseases/fruit_brown_rot.jpeg" alt="Fruit Brown Rot">
      <h3>81. Fruit Brown Rot</h3>
      <p><strong>Cause:</strong> Monilinia and similar fungi.</p>
      <p><strong>Symptoms:</strong> Brown rotting of fruit; mummification.</p>
      <p><strong>Treatment:</strong> Remove mummies; apply fungicides at bloom and pre-harvest.</p>
    </div>

    <!-- 82 -->
    <div class="card">
      <img src="Images/diseases/leaf_blotch_general.jpeg" alt="Leaf Blotch">
      <h3>82. Leaf Blotch (General)</h3>
      <p><strong>Cause:</strong> Various fungi causing blotches.</p>
      <p><strong>Symptoms:</strong> Irregular blotches on leaves.</p>
      <p><strong>Treatment:</strong> Sanitation; fungicide if damaging.</p>
    </div>

    <!-- 83 -->
    <div class="card">
      <img src="Images/diseases/lesion_nematode_damage.jpeg" alt="Lesion Nematode">
      <h3>83. Lesion Nematode Damage</h3>
      <p><strong>Cause:</strong> Pratylenchus species causing root lesions.</p>
      <p><strong>Symptoms:</strong> Root lesions, poor uptake, stunting.</p>
      <p><strong>Treatment:</strong> Rotate crops; soil health improvements.</p>
    </div>

    <!-- 84 -->
    <div class="card">
      <img src="Images/diseases/orchid_viruses_general.jpeg" alt="Orchid Virus">
      <h3>84. Orchid Viruses (General)</h3>
      <p><strong>Cause:</strong> Multiple viruses, often sap-transmitted.</p>
      <p><strong>Symptoms:</strong> Mottling, streaks, poor growth.</p>
      <p><strong>Treatment:</strong> Rogue infected plants; sterilize tools.</p>
    </div>

    <!-- 85 -->
    <div class="card">
      <img src="Images/diseases/stem_borer_cane_borer.jpeg" alt="Stem Borer Damage">
      <h3>85. Stem Borer / Cane Borer</h3>
      <p><strong>Cause:</strong> Larvae boring into canes/stems.</p>
      <p><strong>Symptoms:</strong> Cane dieback, sawdust at entry holes.</p>
      <p><strong>Treatment:</strong> Prune out infested canes; destroy them.</p>
    </div>

    <!-- 86 -->
    <div class="card">
      <img src="Images/diseases/bacterial_soft_rot.jpeg" alt="Bacterial Soft Rot">
      <h3>86. Bacterial Soft Rot</h3>
      <p><strong>Cause:</strong> Erwinia and other bacteria on fleshy crops.</p>
      <p><strong>Symptoms:</strong> Soft watery decay with foul smell.</p>
      <p><strong>Treatment:</strong> Improve storage/handling; remove infected produce.</p>
    </div>

    <!-- 87 -->
    <div class="card">
      <img src="Images/diseases/mosaic_on_peppers_beans.jpeg" alt="Mosaic on Peppers/Beans">
      <h3>87. Mosaic on Peppers/Beans</h3>
      <p><strong>Cause:</strong> Viruses transmitted by aphids or thrips.</p>
      <p><strong>Symptoms:</strong> Mosaic patterns, reduced vigor.</p>
      <p><strong>Treatment:</strong> Remove infected, use reflective mulches and insect control.</p>
    </div>

    <!-- 88 -->
    <div class="card">
      <img src="Images/diseases/micronutrient_toxicity_manganese.jpeg" alt="Micronutrient Toxicity">
      <h3>88. Micronutrient Toxicity (e.g., Manganese)</h3>
      <p><strong>Cause:</strong> Excess in soil or low pH.</p>
      <p><strong>Symptoms:</strong> Dark spots, necrosis, chlorosis in patterns.</p>
      <p><strong>Treatment:</strong> Raise pH and avoid over-application of fertilizers.</p>
    </div>

    <!-- 89 -->
    <div class="card">
      <img src="Images/diseases/competition_weed_pressure.jpeg" alt="Competition / Weed Pressure">
      <h3>89. Competition / Weed Pressure</h3>
      <p><strong>Cause:</strong> Weeds stealing resources.</p>
      <p><strong>Symptoms:</strong> Stunted crop growth, reduced yields.</p>
      <p><strong>Treatment:</strong> Mulch, weed control, timely cultivation.</p>
    </div>

    <!-- 90 -->
    <div class="card">
      <img src="Images/diseases/algal_leaf_spot.jpeg" alt="Algal Leaf Spot">
      <h3>90. Algal Leaf Spot</h3>
      <p><strong>Cause:</strong> Algae growing on leaves in wet conditions.</p>
      <p><strong>Symptoms:</strong> Greenish or oval spots on foliage.</p>
      <p><strong>Treatment:</strong> Reduce leaf wetness; copper sprays if needed.</p>
    </div>

    <!-- 91 -->
    <div class="card">
      <img src="Images/diseases/serpentine_leafminer.jpeg" alt="Serpentine Leafminer">
      <h3>91. Serpentine Leafminer</h3>
      <p><strong>Cause:</strong> Fly larvae tunneling between leaf surfaces.</p>
      <p><strong>Symptoms:</strong> Thin winding trails in leaves.</p>
      <p><strong>Treatment:</strong> Remove affected leaves; use row covers or systemic insecticide.</p>
    </div>

    <!-- 92 -->
    <div class="card">
      <img src="Images/diseases/lightning_electrical_damage.jpeg" alt="Lightning / Electrical Damage">
      <h3>92. Lightning / Electrical Damage</h3>
      <p><strong>Cause:</strong> Electrical strikes or contact.</p>
      <p><strong>Symptoms:</strong> Split bark, sudden dieback.</p>
      <p><strong>Treatment:</strong> Remove hazardous branches; consult arborist.</p>
    </div>

    <!-- 93 -->
    <div class="card">
      <img src="Images/diseases/bird_wildlife_damage.jpeg" alt="Bird Damage">
      <h3>93. Bird & Wildlife Damage</h3>
      <p><strong>Cause:</strong> Birds and animals feeding on fruit/leaves.</p>
      <p><strong>Symptoms:</strong> Pecked fruit, torn leaves.</p>
      <p><strong>Treatment:</strong> Netting, scare devices, timely harvest.</p>
    </div>

    <!-- 94 -->
    <div class="card">
      <img src="Images/diseases/manganese_deficiency.jpeg" alt="Manganese Deficiency">
      <h3>94. Manganese Deficiency</h3>
      <p><strong>Cause:</strong> Low manganese availability.</p>
      <p><strong>Symptoms:</strong> Interveinal chlorosis similar to iron but on younger leaves.</p>
      <p><strong>Treatment:</strong> Manganese chelate foliar feed or soil application.</p>
    </div>

    <!-- 95 -->
    <div class="card">
      <img src="Images/diseases/poor_soil_structure_compaction.jpeg" alt="Poor Soil / Compaction">
      <h3>95. Poor Soil Structure / Compaction</h3>
      <p><strong>Cause:</strong> Heavy foot traffic or poor management.</p>
      <p><strong>Symptoms:</strong> Stunted roots, water pooling.</p>
      <p><strong>Treatment:</strong> Incorporate organic matter; aerate or loosen soil.</p>
    </div>

    <!-- 96 -->
    <div class="card">
      <img src="Images/diseases/overwatering.jpeg" alt="Overwatering">
      <h3>96. Overwatering</h3>
      <p><strong>Cause:</strong> Excessive irrigation or poor drainage.</p>
      <p><strong>Symptoms:</strong> Yellowing leaves, root decline, fungal issues.</p>
      <p><strong>Treatment:</strong> Reduce watering; improve drainage and pot mix.</p>
    </div>

    <!-- 97 -->
    <div class="card">
      <img src="Images/diseases/underwatering.jpeg" alt="Underwatering">
      <h3>97. Underwatering</h3>
      <p><strong>Cause:</strong> Insufficient moisture.</p>
      <p><strong>Symptoms:</strong> Wilting, dry brown leaf tips.</p>
      <p><strong>Treatment:</strong> Deep, regular watering and mulching to retain moisture.</p>
    </div>

    <!-- 98 -->
    <div class="card">
      <img src="Images/diseases/fertilizer_burn_salt_build_up.jpeg" alt="Fertilizer Burn">
      <h3>98. Fertilizer Burn / Salt Build-Up</h3>
      <p><strong>Cause:</strong> Excess fertilizer or salts in soil.</p>
      <p><strong>Symptoms:</strong> Leaf margin browning, root damage.</p>
      <p><strong>Treatment:</strong> Leach salts with ample water; reduce fertilizer rates.</p>
    </div>

    <!-- 99 -->
    <div class="card">
      <img src="Images/diseases/tumors_galls_various.jpeg" alt="Tumors / Galls">
      <h3>99. Tumors / Galls (Various)</h3>
      <p><strong>Cause:</strong> Bacteria, nematodes or insects.</p>
      <p><strong>Symptoms:</strong> Localized swellings on roots, stems or leaves.</p>
      <p><strong>Treatment:</strong> Remove affected parts; improve hygiene and rotate crops.</p>
    </div>

    <!-- 100 -->
    <div class="card">
      <img src="Images/diseases/poor_cultural_practices.jpeg" alt="Poor Cultural Practices">
      <h3>100. Poor Cultural Practices</h3>
      <p><strong>Cause:</strong> Wrong plant in wrong place, improper pruning or watering.</p>
      <p><strong>Symptoms:</strong> Chronic poor performance and recurring problems.</p>
      <p><strong>Treatment:</strong> Follow best practices: correct siting, soil prep, pruning and watering.</p>
    </div>

  </div>
</div>

<footer>
  <p>&copy; 2025 Online Plant Nursery — Plant disease guide.</p>
</footer>

</body>
</html>
