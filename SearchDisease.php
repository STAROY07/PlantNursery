<?php
// SearchDisease.php - robust search for your `diseases` table
session_start();
include 'db_connect.php';

if (!isset($conn) || !$conn instanceof mysqli) {
    die("Database connection error.");
}

$search = '';
if (isset($_GET['q'])) $search = trim($_GET['q']);
elseif (isset($_GET['query'])) $search = trim($_GET['query']);

$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$rows = [];

/* -------- helpers (unchanged) -------- */
function table_exists($conn, $table) {
    $t = $conn->real_escape_string($table);
    $res = $conn->query("SHOW TABLES LIKE '$t'");
    return ($res && $res->num_rows > 0);
}
function has_fulltext_index_with_columns($conn, $table, $cols_array) {
    $table_esc = $conn->real_escape_string($table);
    $res = $conn->query("SHOW INDEX FROM `$table_esc` WHERE Index_type = 'FULLTEXT'");
    if (!$res || $res->num_rows === 0) return false;
    $indexes = [];
    while ($r = $res->fetch_assoc()) {
        $indexes[$r['Key_name']][] = $r['Column_name'];
    }
    foreach ($indexes as $idxCols) {
        if (empty(array_diff($cols_array, $idxCols)) && empty(array_diff($idxCols, $cols_array))) return true;
    }
    return false;
}
function resolve_image_path($dbImage) {
    $orig = trim((string)$dbImage);
    if (stripos($orig, 'images/') === 0) $orig = 'Images/' . substr($orig, 7);
    if (preg_match('#^(https?://|data:)#i', $orig)) return $orig;
    if ($orig === '' || !preg_match('#^(\/|Images\/)#i', $orig)) {
        $candidate = 'Images/diseases/' . $orig;
    } else {
        $candidate = ltrim($orig, '/');
    }
    $fullCandidate = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $candidate);
    if (file_exists($fullCandidate)) return $candidate;

    $parts = pathinfo($candidate);
    $dirname = $parts['dirname'] !== '.' ? $parts['dirname'] : 'Images/diseases';
    $basename = $parts['filename'] ?? '';
    foreach (['jpg','jpeg','png','webp','gif'] as $e) {
        $try = $dirname . '/' . $basename . '.' . $e;
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $try))) {
            return $try;
        }
    }
    return false;
}

/* -------- search logic (unchanged) -------- */
$table = 'diseases';
if (!table_exists($conn, $table)) die("Table '$table' not found.");

if ($search !== '') {
    $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
    $boolean = '';
    foreach ($terms as $t) {
        $t = preg_replace('/[^\p{L}\p{N}_-]/u', '', $t);
        if ($t !== '') $boolean .= '+' . $t . ' ';
    }
    $boolean = trim($boolean);

    $ft_columns = ['name','cause','symptoms','treatment'];
    $use_fulltext = ($boolean !== '' && has_fulltext_index_with_columns($conn, $table, $ft_columns));

    if ($use_fulltext) {
        $sql = "SELECT id,name,cause,symptoms,treatment,image,category
                FROM `$table`
                WHERE MATCH(name,cause,symptoms,treatment) AGAINST(? IN BOOLEAN MODE)
                LIMIT 200";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $boolean);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $stmt->close();
        }
    }
    if (empty($rows)) {
        $like = '%' . $search . '%';
        $sql = "SELECT id,name,cause,symptoms,treatment,image,category
                FROM `$table`
                WHERE (name LIKE ? OR cause LIKE ? OR symptoms LIKE ? OR treatment LIKE ?)
                LIMIT 200";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ssss', $like, $like, $like, $like);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Disease Search Results</title>

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
  padding:30px 20px;
  color:#222;
}

/* 🧾 TITLE */
.page-title{
  text-align:center;
  font-size:28px;
  margin-bottom:25px;
  color:#2e7d32;
}

/* 📦 RESULTS WRAPPER */
.results{
  max-width:1100px;
  margin:auto;
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
  gap:22px;
}

/* 🪴 CARD */
.plant{
  background:#ffffff;
  border-radius:18px;
  padding:16px;
  box-shadow:0 15px 45px rgba(0,0,0,0.12);
  display:flex;
  gap:14px;
  align-items:flex-start;
  transition:0.35s ease;
  position:relative;
}

.plant:hover{
  transform:translateY(-6px);
  box-shadow:0 25px 70px rgba(0,0,0,0.18);
}

/* 🖼 IMAGE */
.plant img{
  width:140px;
  height:100px;
  object-fit:cover;
  border-radius:12px;
  flex-shrink:0;
}

/* 📝 CONTENT */
.plant h3{
  font-size:18px;
  color:#c62828;
  margin-bottom:6px;
}

.plant p{
  font-size:13.5px;
  margin:3px 0;
  color:#333;
}

.plant small{
  color:#666;
}

/* ❌ STATES */
.no-results{
  text-align:center;
  font-size:18px;
  color:#777;
  margin-top:40px;
}

.missing-file{
  color:#c62828;
  font-size:12px;
  margin-bottom:6px;
}

/* 📱 RESPONSIVE */
@media(max-width:600px){
  .plant{
    flex-direction:column;
    align-items:center;
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

<h2 class="page-title">
  🔍 Search Results for “<?php echo htmlspecialchars($search); ?>”
</h2>

<?php if ($search === ''): ?>
  <p class="no-results">Type something to search plant diseases.</p>
<?php else: ?>
  <?php if (count($rows) > 0): ?>
    <div class="results">
      <?php foreach ($rows as $row): ?>
        <div class="plant">
          <?php
            $dbImage = $row['image'] ?? '';
            $resolved = resolve_image_path($dbImage);
            if ($resolved === false) {
                echo '<div class="missing-file">Image missing</div>';
                $imgPath = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
            } else {
                $imgPath = $resolved;
            }
          ?>
          <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div>
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><b>Cause:</b> <?php echo htmlspecialchars($row['cause'] ?? ''); ?></p>
            <p><b>Symptoms:</b> <?php echo htmlspecialchars($row['symptoms'] ?? ''); ?></p>
            <p><b>Treatment:</b> <?php echo htmlspecialchars($row['treatment'] ?? ''); ?></p>
            <p><small>Category: <?php echo htmlspecialchars($row['category'] ?? ''); ?></small></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="no-results">No diseases found.</p>
  <?php endif; ?>
<?php endif; ?>

</body>
</html>
