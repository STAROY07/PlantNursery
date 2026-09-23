<?php
// SearchFruit_debug.php
error_reporting(E_ALL);
ini_set('display_errors',1);

// DB settings — update only if your credentials differ
$host = "localhost";
$user = "root";
$pass = "VP22@patil";   // your MySQL password or "" if none
$db   = "userinfo1";    // your DB name

// connect
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("<h2 style='color:red'>DB Connect Error:</h2><pre>" . htmlspecialchars($conn->connect_error) . "</pre>");
}
$conn->set_charset("utf8mb4");

echo "<h3>Debug - Fruit Search</h3>";
echo "<p>Connected to DB: <strong>" . htmlspecialchars($db) . "</strong></p>";

// check table & counts
$tbl = $conn->query("SHOW TABLES LIKE 'searchfruit'");
if (!$tbl || $tbl->num_rows === 0) {
    die("<p style='color:red'>Table <code>searchfruit</code> not found in DB <strong>$db</strong>.</p>");
}

$countAll = $conn->query("SELECT COUNT(*) AS c FROM searchfruit")->fetch_assoc()['c'];
$countFruit = $conn->query("SELECT COUNT(*) AS c FROM searchfruit WHERE LOWER(TRIM(category)) = 'fruit'")->fetch_assoc()['c'];
echo "<p>Total rows in <code>searchfruit</code>: <strong>$countAll</strong> — rows with category 'Fruit' (case-insensitive): <strong>$countFruit</strong></p>";

// list first 6 rows sample
echo "<h4>Sample rows (first 6):</h4><pre>";
$res = $conn->query("SELECT id,name,category FROM searchfruit ORDER BY id LIMIT 6");
while ($r = $res->fetch_assoc()) {
    echo "{$r['id']}. {$r['name']} — category: {$r['category']}\n";
}
echo "</pre>";

// get search term
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
echo "<p>Search term received: <strong>" . htmlspecialchars($q) . "</strong></p>";
if ($q === '') {
    echo "<p style='color:blue'>Use ?q=term in URL (e.g. ?q=Mango) to test.</p>";
    exit;
}

// prepare and run query exactly like your code
$category = 'Fruit';
$like = '%' . $q . '%';
$sql = "SELECT id, name, description, price, image FROM searchfruit
        WHERE LOWER(category) = LOWER(?)
        AND (LOWER(name) LIKE LOWER(?) OR LOWER(description) LIKE LOWER(?))";

echo "<h4>Prepared SQL (for debugging):</h4>";
echo "<pre>" . htmlspecialchars($sql) . "\n\nBindings:\ncategory = " . htmlspecialchars($category) . "\nlike = " . htmlspecialchars($like) . "\n</pre>";

// prepare
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
    echo "<p style='color:orange'><strong>No results.</strong> Try these checks:</p>";
    echo "<ol>
            <li>Run this in phpMyAdmin: <code>SELECT * FROM searchfruit WHERE name LIKE \"%".htmlspecialchars($q)."%\";</code></li>
            <li>Check for hidden spaces or characters: <code>SELECT id,name,CHAR_LENGTH(name) FROM searchfruit WHERE name LIKE \"%".htmlspecialchars(substr($q,0,3))."%\";</code></li>
            <li>Check category values: <code>SELECT DISTINCT category FROM searchfruit;</code></li>
          </ol>";
    // also list close matches using simple LIKE across name only
    $like2 = '%' . substr($q,0,3) . '%';
    $res2 = $conn->query("SELECT id,name FROM searchfruit WHERE LOWER(name) LIKE LOWER('".$conn->real_escape_string($like2)."') LIMIT 20");
    echo "<h4>Close matches (by first 3 chars):</h4><pre>";
    while ($r = $res2->fetch_assoc()) {
        echo "{$r['id']}. {$r['name']}\n";
    }
    echo "</pre>";
    exit;
}

// display results
echo "<h4>Results:</h4>";
foreach ($rows as $r) {
    $img = htmlspecialchars($r['image']);
    echo "<div style='border:1px solid #ddd;padding:10px;margin:8px;border-radius:6px;display:flex;gap:12px;align-items:center'>";
    echo "<div style='width:120px'><img src='images/fruit/{$img}' alt='' style='width:100%;height:70px;object-fit:cover'></div>";
    echo "<div><strong>" . htmlspecialchars($r['name']) . "</strong><br>";
    echo htmlspecialchars($r['description']) . "<br>";
    echo "<em>₹" . htmlspecialchars($r['price']) . "</em></div>";
    echo "</div>";
}

echo "<p style='color:green'>If results displayed, copy the prepared SQL and bindings into your real <code>SearchFruit.php</code> — it should behave the same.</p>";

$conn->close();
?>
