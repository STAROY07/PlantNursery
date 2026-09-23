<?php
// SearchFruit.php - fixed version (returns relative image paths)
// Requires: db_connect.php that creates $conn (mysqli)

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db_connect.php';
if (!isset($conn) || !$conn instanceof mysqli) {
    die("Database connection error. Check db_connect.php.");
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) && trim($_GET['category']) !== '' ? trim($_GET['category']) : 'Fruit';
$rows = [];

// Prepared search
if ($search !== '') {
    $like = '%' . $search . '%';
    $sql = "SELECT id, name, description, price, image
            FROM searchfruit
            WHERE LOWER(category) = LOWER(?)
              AND (LOWER(name) LIKE LOWER(?) OR LOWER(description) LIKE LOWER(?))
            LIMIT 500";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sss', $category, $like, $like);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        $stmt->close();
    } else {
        error_log("Prepare failed (SearchFruit): " . $conn->error);
    }
}

// Image resolver -> returns relative path like "Images/fruit/apple.jpg" or full URL
function resolve_fruit_image($dbImage) {
    $orig = trim((string)$dbImage);
    $orig = str_replace('\\', '/', $orig);

    $placeholder_rel = 'Images/placeholder.png';
    $placeholder_fs = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $placeholder_rel);

    if ($orig === '') {
        return is_file($placeholder_fs) ? $placeholder_rel : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
    }

    if (preg_match('#^(https?://|data:)#i', $orig)) return $orig;

    if (stripos($orig, 'images/') === 0 || stripos($orig, 'image/') === 0) {
        $orig = 'Images/' . preg_replace('#^images?/+#i', '', $orig);
    }

    if (strpos($orig, '/') === 0 || stripos($orig, 'Images/') === 0) {
        $candidate = ltrim($orig, '/');
    } else {
        $candidate = 'Images/fruit/' . $orig;
    }

    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
    if ($docRoot !== '') {
        $fs1 = $docRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $candidate);
        if (is_file($fs1)) return $candidate;
    }

    $fs2 = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $candidate);
    if (is_file($fs2)) return $candidate;

    // try extensions
    $parts = pathinfo($candidate);
    $dirname = isset($parts['dirname']) && $parts['dirname'] !== '.' ? $parts['dirname'] : 'Images/fruit';
    $basename = $parts['filename'] ?? ($parts['basename'] ?? '');
    $exts = ['jpg','jpeg','png','webp','gif'];

    if ($basename !== '') {
        foreach ($exts as $e) {
            $try = $dirname . '/' . $basename . '.' . $e;
            if ($docRoot !== '') {
                $fsTry1 = $docRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $try);
                if (is_file($fsTry1)) return $try;
            }
            $fsTry2 = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $try);
            if (is_file($fsTry2)) return $try;
        }
    }

    // case-insensitive scan
    $dirFull = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $dirname);
    if (is_dir($dirFull) && $basename !== '') {
        foreach (scandir($dirFull) as $f) {
            if ($f === '.' || $f === '..') continue;
            $p = pathinfo($f);
            if (isset($p['filename']) && strcasecmp($p['filename'], $basename) === 0) {
                return $dirname . '/' . $f;
            }
            if (strcasecmp($f, ($parts['basename'] ?? '')) === 0) {
                return $dirname . '/' . $f;
            }
        }
    }

    return is_file($placeholder_fs) ? $placeholder_rel : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Fruit Plant Search Results</title>
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
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:16px;
  max-width:1100px;
  margin:0 auto 20px;
}

h2{
  color:#2e7d32;
  font-size:26px;
}

/* 🔍 SEARCH BAR */
.search-small{
  display:flex;
  gap:6px;
}

.search-small input{
  padding:10px 14px;
  width:260px;
  border-radius:30px;
  border:1px solid #ccc;
  font-size:14px;
  outline:none;
  transition:0.3s;
}

.search-small input:focus{
  border-color:#4CAF50;
  box-shadow:0 0 0 3px rgba(76,175,80,0.15);
}

.search-small button{
  padding:10px 18px;
  border-radius:30px;
  border:none;
  background:linear-gradient(135deg,#2e7d32,#4CAF50);
  color:#fff;
  cursor:pointer;
  font-weight:600;
  box-shadow:0 6px 16px rgba(0,0,0,0.2);
  transition:0.3s;
}

.search-small button:hover{
  transform:translateY(-2px);
  box-shadow:0 10px 26px rgba(0,0,0,0.3);
}

/* 🪴 RESULT CARD */
.plant{
  max-width:1100px;
  margin:14px auto;
  padding:14px;
  display:flex;
  gap:16px;
  background:#ffffff;
  border-radius:16px;
  box-shadow:0 10px 35px rgba(0,0,0,0.12);
  align-items:center;
  transition:0.35s ease;
}

.plant:hover{
  transform:translateY(-6px);
  box-shadow:0 20px 55px rgba(0,0,0,0.18);
}

/* 🖼 IMAGE */
.plant img{
  width:150px;
  height:100px;
  object-fit:cover;
  border-radius:12px;
  flex-shrink:0;
}

/* 📝 TEXT */
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

/* 🚫 NO RESULTS */
.no-results{
  text-align:center;
  font-size:18px;
  color:#777;
  margin-top:30px;
}

/* ℹ SMALL NOTE */
.small-note{
  text-align:center;
  font-size:13px;
  color:#999;
  margin-top:10px;
}

/* 📱 RESPONSIVE */
@media(max-width:600px){
  .header{
    flex-direction:column;
    align-items:flex-start;
  }
  .search-small input{
    width:100%;
  }
  .plant{
    flex-direction:column;
    text-align:center;
  }
  .plant img{
    width:100%;
    height:160px;
  }
}
</style>

</head>
<body>

<div class="header">
  <h2>Search Results for "<?php echo htmlspecialchars($search); ?>"</h2>
  <div class="search-small">
    <form action="SearchFruit.php" method="get" style="display:flex;align-items:center;gap:8px">
      <input type="text" name="q" placeholder="Search fruit plants" value="<?php echo htmlspecialchars($search); ?>" required>
      <button type="submit">Search</button>
    </form>
  </div>
</div>

<?php if ($search === ''): ?>
  <p class="no-results">Type a name (e.g., Mango, Amla, Guava) in the search box and press Search.</p>
<?php else: ?>

  <?php if (count($rows) > 0): ?>
    <?php foreach ($rows as $row): ?>
      <?php
        $imgRel = resolve_fruit_image($row['image'] ?? '');
        $imgSrc = preg_match('#^https?://#i', $imgRel) ? $imgRel : $imgRel;
      ?>
      <div class="plant">
        <div>
          <img src="<?php echo htmlspecialchars($imgSrc); ?>"
               alt="<?php echo htmlspecialchars($row['name']); ?>"
               onerror="this.onerror=null;this.src='Images/placeholder.png';">
        </div>
        <div>
          <h3><?php echo htmlspecialchars($row['name']); ?></h3>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
          <p><strong>₹<?php echo htmlspecialchars($row['price']); ?></strong></p>
        </div>
      </div>
    <?php endforeach; ?>

    <p class="small-note">If images still fail: confirm files exist in <code>Images/fruit/</code>, check filename case, and ensure files are readable by the webserver (chmod 644).</p>

  <?php else: ?>
    <p class="no-results">No fruit plants found. Try different keywords or check spelling.</p>
  <?php endif; ?>

<?php endif; ?>

</body>
</html>
