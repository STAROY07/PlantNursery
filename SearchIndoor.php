<?php
session_start();
include 'db_connect.php';  // your connection file

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$query = '';
if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
}

// If you want to show all when empty, set $showAll = true; else false
$showAll = false;

if ($query === '' && !$showAll) {
    $rows = [];
} else {
    // prepare case-insensitive search on name and description
    $like = '%' . mb_strtolower($query, 'UTF-8') . '%';
    $sql = "SELECT * FROM indoor_plants WHERE LOWER(plant_name) LIKE ? OR LOWER(plant_description) LIKE ? ORDER BY plant_name";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Indoor Plants</title>
    <meta charset="utf-8">
    <style>
      /* small styling to match your grid */
      .grid { display:flex; flex-wrap:wrap; justify-content:center; gap:16px; }
      .card { border:1px solid #ccc; margin:8px; padding:12px; width:220px; text-align:center; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.06); background:#fff; }
      .card img { width:200px; height:200px; object-fit:cover; border-radius:4px; }
      .price { font-weight:700; margin-top:6px; }
      .nores { text-align:center; color:#666; margin-top:30px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Search Results for: <b><?php echo htmlspecialchars($query); ?></b></h2>

<?php if (empty($rows)): ?>
    <p class="nores">
        <?php if ($query === ''): ?>
            Please enter a search term.
        <?php else: ?>
            No results found for "<strong><?php echo htmlspecialchars($query); ?></strong>".
        <?php endif; ?>
    </p>
<?php else: ?>
    <div class="grid">
    <?php foreach ($rows as $row): ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($row['plant_image']); ?>" alt="<?php echo htmlspecialchars($row['plant_name']); ?>">
            <h3><?php echo htmlspecialchars($row['plant_name']); ?></h3>
            <p style="font-size:14px; min-height:40px;"><?php echo htmlspecialchars($row['plant_description']); ?></p>
            <p class="price">₹<?php echo number_format((float)$row['plant_price'], 2); ?></p>

            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="plant_id" value="<?php echo (int)$row['id']; ?>">
                <button type="submit" style="background:#4CAF50;color:#fff;border:none;padding:8px 12px;border-radius:4px;cursor:pointer;">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
