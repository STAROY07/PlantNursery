<?php
// SearchMedicinal_debug.php — temporary debug only
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// DB include (if you already have db_Medicinal_connect.php you can include it instead)
// If you include, comment the manual connection block below and uncomment include.
// include 'db_Medicinal_connect.php';

$host = "localhost";
$user = "root";
$pass = "VP22@patil";   // change if needed
$db   = "userinfo1";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("<p style='color:red'>DB Connect Error: " . htmlspecialchars($conn->connect_error) . "</p>");
}
$conn->set_charset("utf8mb4");

echo "<h3>Medicinal Search Debug</h3>";
echo "<p>Connected to DB: <strong>" . htmlspecialchars($db) . "</strong></p>";

// verify table exists
$tbl = $conn->query("SHOW TABLES LIKE 'searchmedicinalplants'");
if (!$tbl || $tbl->num_rows === 0) {
    die("<p style='color:red'>Table <code>searchmedicinalplants</code> NOT found in DB <strong>$db</strong>. Check table name.</p>");
}

$countAll = $conn->query("SELECT COUNT(*) AS c FROM searchmedicinalplants")->fetch_assoc()['c'];
echo "<p>Total rows in <code>searchmedicinalplants</code>: <strong>$countAll</strong></p>";

// show sample rows
echo "<h4>Sample rows:</h4><pre>";
$res = $conn->query("SELECT id,name,category FROM searchmedicinalplants ORDER BY id LIMIT 6");
while ($r = $res->fetch_assoc()) {
    echo "{$r['id']}. {$r['name']} — category: {$r['category']}\n";
}
echo "</pre>";

// show GET param
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
echo "<p>Search term received (q): <strong>" . htmlspecialchars($q) . "</strong></p>";

if ($q === '') {
    echo "<p style='color:blue'>No search term. Add ?q=Tulsi to the URL to test.</p>";
    exit;
}

// run the same prepared search you should have in SearchMedicinal.php
$category = 'Medicinal';
$like = '%' . $q . '%';
$sql = "SELECT id,name,description,price,image FROM searchmedicinalplants
        WHERE LOWER(category) = LOWER(?)
          AND (LOWER(name) LIKE LOWER(?) OR LOWER(description) LIKE LOWER(?))";

echo "<pre>Prepared SQL:\n$sql\nBindings: category=$category, like=$like</pre>";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("<p style='color:red'>Prepare failed: " . htmlspecialchars($conn->error) . "</p>");
}
$stmt->bind_param('sss', $category, $like, $like);
if (!$stmt->execute()) {
    die("<p style='color:red'>Execute failed: " . htmlspecialchars($stmt->error) . "</p>");
}
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo "<p>Rows returned: <strong>" . count($rows) . "</strong></p>";
if (count($rows) === 0) {
    echo "<p style='color:orange'>No results. Try direct SQL in phpMyAdmin:<br>
          <code>SELECT * FROM searchmedicinalplants WHERE name LIKE '%" . $conn->real_escape_string($q) . "%';</code></p>";
    // show close matches by name fragment
    $like2 = '%' . substr($q, 0, 3) . '%';
    $res2 = $conn->query("SELECT id,name FROM searchmedicinalplants WHERE LOWER(name) LIKE LOWER('".$conn->real_escape_string($like2)."') LIMIT 20");
    echo "<h4>Close matches (first 3 chars):</h4><pre>";
    while ($r = $res2->fetch_assoc()) {
        echo "{$r['id']}. {$r['name']}\n";
    }
    echo "</pre>";
    exit;
}

// display results
foreach ($rows as $r) {
    echo "<div style='border:1px solid #ddd;padding:12px;margin:8px;border-radius:6px;display:flex;gap:12px;align-items:center'>";
    $img = htmlspecialchars($r['image']);
    echo "<div style='width:120px'><img src='images/medicinal/{$img}' style='width:100%;height:80px;object-fit:cover' alt=''></div>";
    echo "<div><strong>" . htmlspecialchars($r['name']) . "</strong><br>";
    echo htmlspecialchars($r['description']) . "<br>";
    echo "₹" . htmlspecialchars($r['price']) . "</div></div>";
}

$conn->close();
?>
