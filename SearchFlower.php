<?php
// SearchFlower.php - safe & compatible with your `searchflower` table
session_start();
ini_set('display_errors', 0); // set 1 only while debugging
error_reporting(E_ALL);

include 'db_connect.php'; // must create $conn (mysqli)
if (!isset($conn) || !$conn instanceof mysqli) {
    die("Database connection error.");
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : 'Flower';
$rows = [];

// Helper: does table exist?
function table_exists($conn, $table) {
    $t = $conn->real_escape_string($table);
    $res = $conn->query("SHOW TABLES LIKE '$t'");
    return ($res && $res->num_rows > 0);
}

// Helper: check for FULLTEXT index that matches exactly given columns (set match)
function has_fulltext_index_with_columns($conn, $table, $cols_array) {
    $table_esc = $conn->real_escape_string($table);
    $res = $conn->query("SHOW INDEX FROM `$table_esc` WHERE Index_type = 'FULLTEXT'");
    if (!$res || $res->num_rows === 0) return false;
    // Collect columns per index
    $indexes = [];
    while ($r = $res->fetch_assoc()) {
        $indexes[$r['Key_name']][] = $r['Column_name'];
    }
    foreach ($indexes as $idxCols) {
        // Compare sets - order not important
        $diff1 = array_diff($cols_array, $idxCols);
        $diff2 = array_diff($idxCols, $cols_array);
        if (empty($diff1) && empty($diff2)) return true;
    }
    return false;
}

// Make sure table exists
$table = 'searchflower';
if (!table_exists($conn, $table)) {
    die("Table '$table' not found in database.");
}

if ($search !== '') {
    // Build cleaned boolean string for FULLTEXT boolean mode
    $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
    $boolean = '';
    foreach ($terms as $t) {
        // allow letters, numbers, underscore, hyphen; remove other special chars
        $t = preg_replace('/[^\p{L}\p{N}_-]/u', '', $t);
        if ($t !== '') $boolean .= '+' . $t . ' ';
    }
    $boolean = trim($boolean);

    // Decide if we can safely use MATCH...AGAINST (requires a matching FULLTEXT index)
    $ft_columns = ['name', 'short_description', 'description', 'tags'];
    $use_fulltext = false;
    if ($boolean !== '' && has_fulltext_index_with_columns($conn, $table, $ft_columns)) {
        $use_fulltext = true;
    }

    if ($use_fulltext) {
        // FULLTEXT boolean search (safe because we checked index exists)
        $sql = "SELECT id, name, short_description, description, price, image
                FROM `$table`
                WHERE category COLLATE utf8mb4_general_ci = ?
                  AND MATCH(name, short_description, description, tags)
                      AGAINST(? IN BOOLEAN MODE)
                LIMIT 200";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $category, $boolean);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $stmt->close();
        }
    }

    // Fallback to LIKE search if no fulltext used or returned nothing
    if (empty($rows)) {
        $like = '%' . $search . '%';
        $sql = "SELECT id, name, short_description, description, price, image
                FROM `$table`
                WHERE category COLLATE utf8mb4_general_ci = ?
                  AND (name LIKE ? OR short_description LIKE ? OR description LIKE ? OR tags LIKE ?)
                LIMIT 200";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssss', $category, $like, $like, $like, $like);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $stmt->close();
        }
    }
}

// ----------------------
// Image resolver helper
// ----------------------
// This helper tries:
//  - If DB value is full path or URL -> use as-is (with Images/ normalization)
//  - If DB value is filename only -> prefix 'Images/flower/'
//  - Try alternative extensions (.jpg, .jpeg, .png, .webp, .gif)
//  - Case-insensitive match in directory as last resort
function resolve_image_path($dbImage) {
    // if empty -> use placeholder filename (will be resolved below)
    $orig = trim((string)$dbImage);

    // Normalize common 'images/' to 'Images/'
    if (stripos($orig, 'images/') === 0) {
        $orig = 'Images/' . substr($orig, 7);
    }

    // If it's a URL (http:// or https://) or data URI, return as-is
    if (preg_match('#^(https?://|data:)#i', $orig)) {
        return $orig;
    }

    // Candidate path: if orig seems like absolute (starts with / or Images/), use it,
    // otherwise treat as filename and prefix with Images/flower/
    if ($orig === '' || !preg_match('#^(\/|Images\/)#i', $orig)) {
        $candidate = 'Images/flower/' . $orig;
    } else {
        $candidate = $orig;
    }

    // If candidate exists on filesystem, return it
    $fullCandidate = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $candidate);
    if (file_exists($fullCandidate)) {
        return $candidate;
    }

    // Try extension variants if candidate has a basename
    $parts = pathinfo($candidate);
    $dirname = isset($parts['dirname']) && $parts['dirname'] !== '.' ? $parts['dirname'] : 'Images/flower';
    $basename = isset($parts['filename']) ? $parts['filename'] : $parts['basename'];
    $exts = ['jpg','jpeg','png','webp','gif'];

    foreach ($exts as $e) {
        $try = $dirname . '/' . $basename . '.' . $e;
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $try))) {
            return $try;
        }
    }

    // As a last attempt, do a case-insensitive scan of the directory for matching filename
    $dirFull = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $dirname);
    if (is_dir($dirFull)) {
        $files = scandir($dirFull);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            // match filename ignoring extension
            $p = pathinfo($f);
            if (isset($p['filename']) && strcasecmp($p['filename'], $basename) === 0) {
                return $dirname . '/' . $f;
            }
            // or exact case-insensitive filename
            if (strcasecmp($f, $parts['basename'] ?? '') === 0) {
                return $dirname . '/' . $f;
            }
        }
    }

    // nothing found; return false to indicate missing
    return false;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Search Results</title>
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
  justify-content:space-between;
  align-items:center;
  max-width:1100px;
  margin:auto;
  margin-bottom:20px;
}

h2{
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
  cursor:pointer;
  font-weight:600;
  transition:0.3s;
}

.search-small button:hover{
  transform:translateY(-2px);
}

/* 🍎 RESULT CARD (SAME AS FLOWER) */
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

/* 💰 PRICE */
.price{
  font-weight:700;
  color:#2e7d32;
}

/* 🚫 NO RESULTS */
.no-results{
  text-align:center;
  font-size:18px;
  color:#777;
  margin-top:30px;
}

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
    height:160px;
  }
}
</style>


</head>
<body>
<div class="header">
  <h2>🌸 Search Results for "<?php echo htmlspecialchars($search); ?>"</h2>

  <form class="search-small" method="get" action="SearchFlower.php">
    <input
      type="text"
      name="q"
      placeholder="Search flowers..."
      value="<?php echo htmlspecialchars($search); ?>"
      required
    >
    <button type="submit">Search</button>
  </form>
</div>


  <?php if ($search === ''): ?>
    <p>Type something to search for flower plants.</p>
  <?php else: ?>
    <?php if (count($rows) > 0): ?>
      <?php foreach ($rows as $row): ?>
        <div class="plant">

          <?php
            // Resolve image path using helper
            $dbImage = $row['image'] ?? '';
            $resolved = resolve_image_path($dbImage);

            // Debug HTML comment (view page source to inspect). Remove if not needed.
            if ($resolved === false) {
                echo '<!-- IMG DEBUG: DB=' . htmlspecialchars($dbImage) . ' | resolved=NOT_FOUND -->';
            } else {
                echo '<!-- IMG DEBUG: DB=' . htmlspecialchars($dbImage) . ' | resolved=' . htmlspecialchars($resolved) . ' -->';
            }

            if ($resolved === false) {
                // show missing-file message and use placeholder fallback if available
                echo '<div class="missing-file">Image missing: ' . htmlspecialchars('Images/flower/' . ($dbImage ?: '(empty)')) . '</div>';
                $fallback = 'Images/placeholder.jpeg';
                if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $fallback))) {
                    $imgPath = $fallback;
                } else {
                    $imgPath = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                }
            } else {
                $imgPath = $resolved;
            }
          ?>

          <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">

          <div>
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><?php echo htmlspecialchars(!empty($row['short_description']) ? $row['short_description'] : $row['description']); ?></p>
            <p>₹<?php echo htmlspecialchars($row['price']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="no-results">No plants found!</p>
    <?php endif; ?>
  <?php endif; ?>
</body>
</html>
