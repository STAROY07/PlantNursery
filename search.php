<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "VP22@patil", "plant_nursery");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if (isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conn, $_GET['query']);

    $sql = "SELECT * FROM plants WHERE plant_name LIKE '%$search%' OR plant_type LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>

<h2 style="text-align:center;">Search Results for: <b><?php echo $search; ?></b></h2>

<div style="display:flex; flex-wrap:wrap; justify-content:center;">
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <div style='border:1px solid #ccc; padding:15px; margin:10px; width:250px;'>
            <img src='{$row['image']}' width='200' height='200'><br>
            <h3>{$row['plant_name']}</h3>
            <p>{$row['description']}</p>
            <p><b>Price: ₹{$row['price']}</b></p>
            <a href='add_to_cart.php?id={$row['id']}'>
                <button>Add to Cart</button>
            </a>
        </div>
        ";
    }
} else {
    echo "<p style='text-align:center; font-size:20px;'>No plants found 😕</p>";
}
?>
</div>

</body>
</html>
