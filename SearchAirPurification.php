<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

/* 🔍 SEARCH INPUT */
$query = '';
if (isset($_GET['q'])) {
    $query = trim($_GET['q']);
    $query = str_ireplace(
        ['airpurify','air-purify','airpurification'],
        'air purification',
        $query
    );
}

$rows = [];

if ($query !== '') {
    $like = '%' . mb_strtolower($query, 'UTF-8') . '%';

    $sql = "SELECT * FROM indoor_plants
            WHERE LOWER(plant_name) LIKE ?
               OR LOWER(plant_description) LIKE ?
            ORDER BY plant_name";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Air Purification Plants</title>

<style>
/* 🌿 GLOBAL */
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body{
  background: linear-gradient(135deg,#f1f8e9,#ffffff);
  padding:24px;
  color:#222;
}

/* 🔝 HEADER */
.header{
  max-width:1100px;
  margin:auto;
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:20px;
}

.header h2{
  color:#2e7d32;
  font-size:26px;
}

/* 🔍 SEARCH BAR */
.search-small{
  display:flex;
  gap:8px;
}

.search-small input{
  padding:10px 14px;
  width:260px;
  border-radius:30px;
  border:1px solid #ccc;
  font-size:14px;
}

.search-small button{
  padding:10px 18px;
  border-radius:30px;
  border:none;
  background:linear-gradient(135deg,#2e7d32,#4CAF50);
  color:#fff;
  font-weight:600;
  cursor:pointer;
}

/* 🌱 RESULT CARD (SAME AS FLOWER & FRUIT) */
.plant{
  max-width:1100px;
  margin:14px auto;
  padding:14px;
  display:flex;
  gap:16px;
  background:#ffffff;
  border-radius:16px;
  box-shadow:0 12px 38px rgba(0,0,0,0.12);
  align-items:center;
  transition:0.35s ease;
}

.plant:hover{
  transform:translateY(-6px);
  box-shadow:0 22px 60px rgba(0,0,0,0.18);
}

/* 🖼 IMAGE */
.plant img{
  width:150px;
  height:110px;
  object-fit:cover;
  border-radius:12px;
  flex-shrink:0;
}

/* 📝 CONTENT */
.plant h3{
  margin-bottom:6px;
  color:#c62828;
  font-size:18px;
}

.plant p{
  font-size:14px;
  color:#555;
  margin:4px 0;
}

/* 💰 PRICE */
.price{
  font-weight:700;
  color:#2e7d32;
  margin-top:6px;
}

/* 🛒 BUTTON */
.add-cart{
  margin-top:10px;
  padding:8px 18px;
  border:none;
  border-radius:20px;
  background:#4CAF50;
  color:#fff;
  font-weight:600;
  cursor:pointer;
}

/* 🚫 NO RESULTS */
.nores{
  text-align:center;
  font-size:18px;
  color:#777;
  margin-top:30px;
}

/* 📱 RESPONSIVE */
@media(max-width:600px){
  .header{
    flex-direction:column;
    gap:12px;
    align-items:flex-start;
  }
  .plant{
    flex-direction:column;
    text-align:center;
  }
  .plant img{
    width:100%;
    height:180px;
  }
}
</style>
</head>

<body>

<div class="header">
  <h2>Search Results for "<b><?php echo htmlspecialchars($query); ?></b>"</h2>

  <form class="search-small" method="GET">
      <input type="text" name="q" placeholder="Search air purification plants" value="<?php echo htmlspecialchars($query); ?>">
      <button type="submit">Search</button>
  </form>
</div>

<?php if ($query === ''): ?>
    <p class="nores">Please enter a search term.</p>

<?php elseif (empty($rows)): ?>
    <p class="nores">No plants found for "<b><?php echo htmlspecialchars($query); ?></b>".</p>

<?php else: ?>
    <?php foreach ($rows as $row): ?>
        <div class="plant">

            <img src="<?php echo htmlspecialchars($row['plant_image']); ?>"
                 alt="<?php echo htmlspecialchars($row['plant_name']); ?>">

            <div>
                <h3><?php echo htmlspecialchars($row['plant_name']); ?></h3>

                <p><?php echo htmlspecialchars($row['plant_description']); ?></p>

                <p class="price">₹<?php echo number_format((float)$row['plant_price'], 2); ?></p>

                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="plant_id" value="<?php echo (int)$row['id']; ?>">
                    <button type="submit" class="add-cart">
                        Add to Cart
                    </button>
                </form>
            </div>

        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
