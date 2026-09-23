<?php
session_start();
include 'db_connect.php';  // your DB file (create if not exists)

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$query = "";
if (isset($_GET["query"])) {
    $query = mysqli_real_escape_string($conn, $_GET["query"]);
}

$sql = "SELECT * FROM searchplantsplant_info WHERE 
        plant_name LIKE '%$query%' 
        OR scientific_name LIKE '%$query%'
        OR description LIKE '%$query%'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>

<h2 style="text-align:center;">Search Results for: <?php echo $query; ?></h2>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div style='width:60%; margin:20px auto; padding:15px; border:1px solid #ccc;'>";
        echo "<h3>" . $row['plant_name'] . "</h3>";
        echo "<p><b>Scientific Name:</b> " . $row['scientific_name'] . "</p>";
        echo "<p>" . $row['description'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p style='text-align:center; font-size:20px;'>No results found for \"$query\".</p>";
}
?>

</body>
</html>
