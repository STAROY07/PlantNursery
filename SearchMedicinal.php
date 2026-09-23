<?php
// SearchMedicinal.php (robust, drop-in)
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'db_connect.php'; // must set $conn (mysqli)
if (!isset($conn) || !$conn instanceof mysqli) {
    die("Database connection error. Check db_connect.php.");
}

// get search term
$search = isset($_REQUEST['q']) ? trim((string)$_REQUEST['q']) : '';
$category = isset($_REQUEST['category']) && trim($_REQUEST['category']) !== '' ? trim($_REQUEST['category']) : 'Medicinal';
$rows = [];

// prepared search
if ($search !== '') {
    $like = '%' . $search . '%';
    $sql = "SELECT id, name, description, price, image, category
            FROM searchmedicinalplants
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
        error_log("Prepare failed: " . $conn->error);
    }
}

// image resolver -> returns relative path like "Images/medicinal/brahmi.jpeg" or placeholder/data-uri
function resolve_medicinal_image($dbImage) {
    $projectDir = __DIR__;
    $orig = trim((string)$dbImage);
    $orig = str_replace('\\','/',$orig);
    $placeholder = 'Images/placeholder.png';
    $placeholderFs = $projectDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $placeholder);

    if ($orig === '') {
        return is_file($placeholderFs) ? $placeholder : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
    }

    // Normalize 'images' -> 'Images' and remove leading slashes
    $orig = preg_replace('#^images?/#i','',$orig);
    $candidates = [
        'Images/medicinal/' . ltrim($orig,'/'),
        'Images/' . ltrim($orig,'/'),
        ltrim($orig,'/')
    ];

    // check exact file candidates
    foreach ($candidates as $cand) {
        $fs = $projectDir . DIRECTORY_SEPARATOR . str_replace(['/','\\'], DIRECTORY_SEPARATOR, $cand);
        if (is_file($fs)) return $cand;
    }

    // try common extensions for basename
    foreach ($candidates as $cand) {
        $parts = pathinfo($cand);
        $dir = ($parts['dirname'] ?? '') ?: 'Images/medicinal';
        $base = $parts['filename'] ?? ($parts['basename'] ?? '');
        if ($base === '') continue;
        foreach (['jpg','jpeg','png','webp','gif'] as $ext) {
            $try = $dir . '/' . $base . '.' . $ext;
            if (is_file($projectDir . DIRECTORY_SEPARATOR . str_replace(['/','\\'], DIRECTORY_SEPARATOR, $try))) return $try;
        }
    }

    // case-insensitive scan of Images/medicinal
    $scanDir = $projectDir . DIRECTORY_SEPARATOR . 'Images' . DIRECTORY_SEPARATOR . 'medicinal';
    if (is_dir($scanDir)) {
        $baseTry = '';
        // choose a base from candidates
        $first = $candidates[0];
        $p = pathinfo($first);
        $baseTry = $p['filename'] ?? '';
        if ($baseTry !== '') {
            foreach (scandir($scanDir) as $f) {
                if ($f==='.'||$f==='..') continue;
                $pf = pathinfo($f);
                if (isset($pf['filename']) && strcasecmp($pf['filename'], $baseTry) === 0) {
                    return 'Images/medicinal/' . $f;
                }
            }
        }
    }

    return is_file($placeholderFs) ? $placeholder : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Medicinal Plant Search Results</title>
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
  margin:0 auto 20px auto;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
}

h2{
  margin:0;
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
  outline:none;
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
  font-weight:600;
  cursor:pointer;
  transition:0.3s ease;
}

.search-small button:hover{
  transform:translateY(-2px);
}

/* 🌱 RESULT CARD (SAME AS FLOWER / FRUIT / AIR) */
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

/* 💰 PRICE */
.price{
  font-weight:700;
  color:#2e7d32;
  margin-top:6px;
}

/* 🛒 BUTTON */
.add-cart{
  margin-top:10px;
  padding:8px 16px;
  border:none;
  border-radius:20px;
  background:#4CAF50;
  color:#fff;
  font-weight:600;
  cursor:pointer;
}

/* 🚫 NO RESULTS */
.no-results{
  text-align:center;
  font-size:18px;
  color:#777;
  margin-top:30px;
}

/* 📝 SMALL NOTES / DEBUG */
.small-note,
.debug{
  max-width:1100px;
  margin:18px auto 0 auto;   /* center horizontally */
  text-align:center;        /* center text */
  font-size:13px;
  color:#999;
/* 📱 RESPONSIVE */
@media(max-width:600px){
  .header{
    flex-direction:column;
    align-items:flex-start;
    gap:12px;
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
    height:180px;
  }
  
}

}
</style>

</head>
<body>

<?php $debug = ['q'=>($search===''?'[empty]':$search),'rows'=>count($rows)]; ?>
<!-- DEBUG: <?php echo htmlspecialchars(json_encode($debug)); ?> -->

<div class="header">
    <h2>🌿 Search Results for "<?php echo htmlspecialchars($query ?? ''); ?>"</h2>

    <form class="search-small" method="get" action="SearchMedicinal.php">
        <input
            type="text"
            name="q"
            value="<?php echo htmlspecialchars($query ?? ''); ?>"
            placeholder="Search medicinal plants..."
        >
        <button type="submit">Search</button>
    </form>
</div>

<?php if ($search === ''): ?>
  <p class="no-results">Please enter a search term.</p>
<?php else: ?>

  <?php if (count($rows) > 0): ?>
    <?php foreach ($rows as $row): 
         $img = resolve_medicinal_image($row['image'] ?? '');
         echo "<!-- IMG MAP: DB=" . htmlspecialchars($row['image'] ?? '') . " => resolved=" . htmlspecialchars($img) . " -->\n";
    ?>
      <div class="plant">
        <div>
          <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"
               onerror="this.onerror=null;this.src='Images/placeholder.png';">
        </div>
        <div>
          <h3><?php echo htmlspecialchars($row['name']); ?></h3>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
          <p><strong>₹<?php echo htmlspecialchars($row['price']); ?></strong></p>
        </div>
      </div>
    <?php endforeach; ?>
    <p class="small-note">If images are broken: verify file exists under <code>Images/medicinal/</code>, check filename case and permissions.</p>
  <?php else: ?>
    <p class="no-results">No medicinal plants found. Try different keywords or check spelling.</p>
  <?php endif; ?>

<?php endif; ?>

</body>
</html>
