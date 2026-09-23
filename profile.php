<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

/* Fetch user */
$res = mysqli_query($conn,
"SELECT name,email,phone,address,profile_pic,created_at 
 FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($res);

/* Counts */
$order_count = mysqli_num_rows(
    mysqli_query($conn,"SELECT id FROM orders WHERE user_id='$user_id'")
);
$wish_count = mysqli_num_rows(
    mysqli_query($conn,"SELECT id FROM wishlist WHERE user_id='$user_id'")
);

/* Update profile */
if (isset($_POST['save_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    mysqli_query($conn,
    "UPDATE users SET name='$name', phone='$phone', address='$address'
     WHERE id='$user_id'");
    $msg = "Profile updated successfully";
}

/* Upload profile photo */
if (isset($_POST['upload_photo'])) {
    if (!is_dir("uploads/profile")) {
        mkdir("uploads/profile", 0777, true);
    }

    $tmp = $_FILES['photo']['tmp_name'];
    if ($tmp) {
        $path = "uploads/profile/".$user_id."_".time().".jpg";
        move_uploaded_file($tmp, $path);
        mysqli_query($conn,
        "UPDATE users SET profile_pic='$path' WHERE id='$user_id'");
        $msg = "Profile photo updated";
    }
}

/* Change password */
if (isset($_POST['change_pass'])) {
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn,
    "UPDATE users SET password='$pass' WHERE id='$user_id'");
    $msg = "Password changed successfully";
}

/* Delete account */
if (isset($_POST['delete_account'])) {
    mysqli_query($conn,"DELETE FROM users WHERE id='$user_id'");
    session_destroy();
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<style>
body{
    font-family:'Segoe UI',sans-serif;
    background: transparent; /* 🔥 use global background */
}

/* 🔥 PROFILE BOX (GLASS EFFECT) */
.profile-box{
    width:500px;
    margin:40px auto;
    padding:30px;

    background: rgba(255,255,255,0.9);
    border-radius:16px;

    box-shadow:0 15px 40px rgba(0,0,0,0.25);
    backdrop-filter: blur(10px);
}

/* TITLE */
h2{
    text-align:center;
    color:#2e7d32;
    text-shadow: 1px 1px 6px rgba(0,0,0,0.3);
}

/* ACTION LINKS */
.profile-actions{
    display:flex;
    justify-content:space-between;
    margin:15px 0 25px;
}
.profile-actions a{
    text-decoration:none;
    font-size:14px;
    color:#2e7d32;
    font-weight:600;
    transition:0.3s;
}
.profile-actions a:hover{
    text-decoration:underline;
}

/* PROFILE PIC */
.profile-pic{
    text-align:center;
    margin-bottom:15px;
}
.profile-pic img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;

    border:4px solid #43a047;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

/* INPUTS */
label{font-weight:600;}

input,textarea{
    width:100%;
    padding:10px;
    margin:6px 0 14px;

    border:none;
    border-radius:8px;

    background: rgba(255,255,255,0.95);
    box-shadow:0 5px 15px rgba(0,0,0,0.1);

    transition:0.3s;
}

input:focus, textarea:focus{
    outline:none;
    box-shadow:0 0 8px rgba(46,125,50,0.5);
}

/* 🔥 BUTTON */
button{
    width:100%;
    padding:11px;

    background: linear-gradient(45deg,#43a047,#66bb6a);
    color:#fff;

    border:none;
    border-radius:8px;
    cursor:pointer;

    font-weight:600;
    transition:0.3s;
}

button:hover{
    transform:scale(1.03);
}

/* DIVIDER */
hr{
    margin:25px 0;
}

/* MESSAGE */
.msg{
    text-align:center;
    color:#2e7d32;
    font-weight:600;
}

/* SMALL TEXT */
.small{
    font-size:13px;
    color:#555;
}

/* LOGOUT */
.logout{
    text-align:center;
    margin-top:15px;
}
.logout a{
    color:red;
    text-decoration:none;
    font-weight:600;
}

/* DELETE BUTTON */
.delete-btn{
    background: linear-gradient(45deg,#e74c3c,#c0392b);
}
.delete-btn:hover{
    transform:scale(1.03);
}
</style>
</head>
<?php include 'global_style.php'; ?>
<body>

<div class="profile-box">
<h2>My Profile</h2>

<div class="profile-actions">
<a href="./orders.php">📦 My Orders (<?php echo $order_count; ?>)</a>
<a href="./wishlist.php">❤️ Wishlist (<?php echo $wish_count; ?>)</a>
<a href="./profile_pdf.php">🧾 Download PDF</a>

</div>

<!-- PROFILE PHOTO -->
<div class="profile-pic">
<img src="<?php echo $user['profile_pic'] ?: 'uploads/profile/default.png'; ?>">
<form method="post" enctype="multipart/form-data">
    <input type="file" name="photo" required>
    <button name="upload_photo">Upload Photo</button>
</form>
</div>

<!-- PROFILE DETAILS -->
<form method="post">
<label>Name</label>
<input type="text" name="name" value="<?php echo $user['name']; ?>" required>

<label>Email</label>
<input type="email" value="<?php echo $user['email']; ?>" disabled>

<label>Mobile</label>
<input type="text" name="phone" value="<?php echo $user['phone']; ?>">

<label>Address</label>
<textarea name="address"><?php echo $user['address']; ?></textarea>

<button name="save_profile">Save Profile</button>
</form>

<hr>

<!-- PASSWORD -->

<div style="text-align:center;">
    <a href="forgot_password.php">
        <button type="button">Forgot / Reset Password</button>
    </a>
    <p class="small">
        You will be redirected to password recovery page
    </p>
</div>


<hr>

<!-- ACCOUNT INFO -->
<p class="small"><b>User ID:</b> <?php echo $user_id; ?></p>
<p class="small"><b>Account Created:</b> <?php echo $user['created_at']; ?></p>

<?php if ($msg): ?>
<p class="msg"><?php echo $msg; ?></p>
<?php endif; ?>

<hr>

<!-- DELETE ACCOUNT -->
<form method="post" onsubmit="return confirm('Are you sure you want to delete your account permanently?');">
    <button name="delete_account" class="delete-btn">Delete My Account</button>
</form>

<div class="logout">
<a href="logout.php">Logout</a>
</div>

<div style="text-align:center; margin-top:25px;">
    <button onclick="goBack()" style="
        padding:10px 30px;
        font-size:15px;
        font-weight:600;
        color:#ffffff;
        background:linear-gradient(135deg,#2ecc71,#27ae60);
        border:none;
        border-radius:30px;
        cursor:pointer;
        box-shadow:0 6px 15px rgba(0,0,0,0.2);
        transition:0.2s;
    "
    onmouseover="this.style.transform='scale(1.05)'"
    onmouseout="this.style.transform='scale(1)'">
        ← Go Back
    </button>
</div>

<script>
function goBack() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
    } else {
        window.location.href = "index.php";
    }
}
</script>

</div>

</body>
</html>
