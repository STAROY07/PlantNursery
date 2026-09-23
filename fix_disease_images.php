<?php
// fix_disease_images.php
// One-time utility: scan Images/diseases/ and normalize 'image' column in 'diseases' table.
//
// WARNING: make a DB backup before running this.

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php'; // must create $conn (mysqli)
if (!isset($conn) || !$conn instanceof mysqli) {
    die("Database connection error.\n");
}

$imagesDir = __DIR__ . DIRECTORY_SEPARATOR . 'Images' . DIRECTORY_SEPARATOR . 'diseases';
if (!is_dir($imagesDir)) {
    die("Images/diseases directory not found at: $imagesDir\n");
}

// Build a map of basename(lowercase) => actual filename (first seen)
$files = scandir($imagesDir);
$fileMap = [];
foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    $p = pathinfo($f);
    if (!isset($p['filename']) || !isset($p['extension'])) continue;
    $key = mb_strtolower($p['filename']);
    // prefer existing (don't overwrite) - keep first occurrence
    if (!isset($fileMap[$key])) $fileMap[$key] = $f;
}

// Helper: sanitize disease name -> common filename form
function sanitize_name_to_basename($name) {
    // Lowercase, convert spaces & non-alnum to underscores, collapse underscores
    $s = mb_strtolower($name);
    $s = preg_replace('/[^\p{L}\p{N}]+/u', '_', $s);
    $s = preg_replace('/_+/', '_', $s);
    $s = trim($s, '_');
    return $s;
}

// Fetch all rows
$res = $conn->query("SELECT id, name, image FROM diseases");
if (!$res) {
    die("Failed to fetch diseases: " . $conn->error);
}

$updates = [];
$unchanged = [];
$matchedBy = []; // id => reason
while ($row = $res->fetch_assoc()) {
    $id = $row['id'];
    $name = $row['name'] ?? '';
    $img = trim((string)($row['image'] ?? ''));

    $foundFilename = null;

    // 1) Try using image column value (strip path and extension)
    if ($img !== '') {
        // remove any leading path like Images/diseases/ or /Images/diseases/
        $base = $img;
        $base = preg_replace('#^/+#', '', $base);
        $base = preg_replace('#^Images/diseases/#i', '', $base);
        $base = preg_replace('#^images/diseases/#i', '', $base);

        // remove extension if any
        $parts = pathinfo($base);
        $baseOnly = isset($parts['filename']) ? $parts['filename'] : $base;
        $key = mb_strtolower($baseOnly);
        if (isset($fileMap[$key])) {
            $foundFilename = $fileMap[$key];
            $matchedBy[$id] = "image-field-match ($img -> $foundFilename)";
        }
    }

    // 2) Try sanitized name -> basename match (if not found yet)
    if ($foundFilename === null && $name !== '') {
        $tryKey = sanitize_name_to_basename($name);
        if ($tryKey !== '' && isset($fileMap[$tryKey])) {
            $foundFilename = $fileMap[$tryKey];
            $matchedBy[$id] = "name-sanitized-match ($name -> $foundFilename)";
        }
    }

    // 3) As a last attempt, try a fuzzy prefix: look for any file where filename contains some word from name
    if ($foundFilename === null && $name !== '') {
        // take first 2 words
        $words = preg_split('/\s+/', preg_replace('/[^\p{L}\p{N}\s]+/u',' ', $name), -1, PREG_SPLIT_NO_EMPTY);
        $candidates = [];
        foreach ($words as $w) {
            if (mb_strlen($w) < 3) continue;
            $wk = mb_strtolower($w);
            foreach ($fileMap as $k => $filename) {
                if (mb_strpos($k, $wk) !== false) {
                    $candidates[] = $filename;
                }
            }
            if (!empty($candidates)) break;
        }
        if (!empty($candidates)) {
            $foundFilename = $candidates[0];
            $matchedBy[$id] = "fuzzy-word-match ($name -> $foundFilename)";
        }
    }

    if ($foundFilename !== null) {
        // We'll store only the filename (no path) to keep DB consistent
        $newValue = $foundFilename;
        // Only update if different (case-sensitive compare)
        if (($row['image'] ?? '') !== $newValue) {
            $updates[] = ['id' => $id, 'old' => $row['image'], 'new' => $newValue, 'note' => $matchedBy[$id] ?? 'matched'];
        } else {
            $unchanged[] = $id;
        }
    } else {
        $updates[] = ['id' => $id, 'old' => $row['image'], 'new' => null, 'note' => 'NO MATCH'];
    }
}

// Confirm & apply updates
echo "<h2>Scan summary</h2>";
echo "<p>Found " . count($fileMap) . " files in Images/diseases/</p>";
echo "<p>Processed rows: " . ($res->num_rows) . "</p>";

// Show planned updates (limit large lists)
echo "<h3>Planned updates (showing up to 200)</h3>";
echo "<table border='1' cellpadding='6' cellspacing='0'><tr><th>ID</th><th>Old image</th><th>New image</th><th>Note</th></tr>";
$counter = 0;
foreach ($updates as $u) {
    if ($counter++ > 200) break;
    echo "<tr>";
    echo "<td>" . htmlspecialchars($u['id']) . "</td>";
    echo "<td>" . htmlspecialchars($u['old']) . "</td>";
    echo "<td>" . htmlspecialchars($u['new'] ?? '(no-match)') . "</td>";
    echo "<td>" . htmlspecialchars($u['note']) . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p><b>Applying updates now...</b></p>";

// Begin transaction if supported
if ($conn->begin_transaction()) {
    $applied = 0;
    $failed = 0;
    $stmt = $conn->prepare("UPDATE diseases SET image = ? WHERE id = ?");
    if (!$stmt) {
        echo "<p style='color:red'>Prepared statement failed: " . htmlspecialchars($conn->error) . "</p>";
        $conn->rollback();
        exit;
    }

    foreach ($updates as $u) {
        if ($u['new'] === null) {
            // skip updating to null; keep existing value (or you can choose to clear it)
            continue;
        }
        $stmt->bind_param('si', $u['new'], $u['id']);
        if ($stmt->execute()) $applied++;
        else $failed++;
    }
    $stmt->close();

    // commit
    $conn->commit();
    echo "<p>Applied: $applied updates. Failed: $failed.</p>";
} else {
    echo "<p style='color:red'>Could not start transaction; aborting.</p>";
}

echo "<p>Rows left unchanged: " . count($unchanged) . "</p>";
echo "<p>Note: rows with 'NO MATCH' were not updated — check Images/diseases/ for appropriate files or rename files to match DB names.</p>";

echo "<p><a href='SearchDisease.php?query=Aphid'>Test search for Aphid</a></p>";
