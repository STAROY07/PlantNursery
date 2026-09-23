<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Remove item from wishlist */
if (isset($_GET['remove'])) {

    $plant_id = $_GET['remove'];

    mysqli_query($conn,
        "DELETE FROM wishlist 
         WHERE plant_id='$plant_id' 
         AND user_id='$user_id'"
    );

    header("Location: wishlist.php");
    exit();
}

/* Fetch wishlist items */
$result = mysqli_query($conn,
"SELECT w.id AS wid, w.plant_id, p.name, p.price, p.image
 FROM wishlist w
 JOIN plants p ON w.plant_id = p.id
 WHERE w.user_id='$user_id'");

?>

<!DOCTYPE html>
<html>
<head>
<title>My Wishlist</title>

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f8;
}

.box{
    width:700px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

h2{
    text-align:center;
    color:#2E8B57;
}

.item{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px 0;
    border-bottom:1px solid #ddd;
}

.item img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:8px;
}

.item-name{
    flex:1;
}

.price{
    color:green;
    font-weight:bold;
}

.remove{
    color:red;
    text-decoration:none;
    font-size:14px;
}

.remove:hover{
    text-decoration:underline;
}

.empty{
    text-align:center;
    color:#666;
    margin-top:20px;
}

.back{
    text-align:center;
    margin-top:20px;
}

.back a{
    text-decoration:none;
    color:#2E8B57;
}

.add-to-cart-btn {
    background: #2E8B57;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s, transform 0.1s;
}
.add-to-cart-btn:hover {
    background: #246d44;
}
.add-to-cart-btn:active {
    transform: scale(0.95);
}
</style>
</head>
<?php include 'global_style.php'; ?>
<body>

<div class="box">
<h2>My Wishlist ❤️</h2>

<?php if (mysqli_num_rows($result) == 0): ?>
    <p class="empty">Your wishlist is empty.</p>
<?php endif; ?>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<div class="item">
    <img src="<?php echo $row['image']; ?>" alt="Plant">
    <div class="item-name">
        <b><?php echo $row['name']; ?></b><br>
        <span class="price">₹<?php echo $row['price']; ?></span>
    </div>
    <div class="actions" style="display:flex; align-items:center; gap:15px;">
        <form action="add_to_cart.php" method="POST" style="margin:0;">
            <input type="hidden" name="plant_id" value="<?php echo $row['plant_id']; ?>">
            <input type="hidden" name="from_wishlist" value="1">
            <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
        </form>

        <a class="remove"
        href="?remove=<?php echo $row['plant_id']; ?>"
           onclick="return confirm('Remove this plant from wishlist?');">
            Remove
        </a>
    </div>
</div>
<?php } ?>

<div class="back">
<a href="javascript:history.back()">← Go Back</a>
</div>

</div>

</body>
</html>
