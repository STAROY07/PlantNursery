<?php
session_start();
include 'db_connect.php';
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify OTP - Plant Nursery</title>

<style>
body{
    background: url('plants-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.verify-box{
    background: rgba(255,255,255,0.95);
    width: 360px;
    padding: 35px;
    border-radius: 10px;
    box-shadow: 0 0 18px rgba(34,139,34,0.4);
}
.verify-box h2{
    text-align: center;
    color: #2E8B57;
    margin-bottom: 20px;
}
.verify-box input{
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 15px;
}
.verify-box button{
    width: 100%;
    padding: 12px;
    background-color: #2E8B57;
    color: #fff;
    border: none;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
}
.verify-box button:hover{
    background-color: #246b45;
}
.msg-error{
    color: red;
    text-align: center;
    margin-top: 10px;
}
</style>
</head>

<body>

<div class="verify-box">
<h2>Verify OTP</h2>

<form method="post">
    <input type="text" name="otp" placeholder="Enter 6-digit OTP" required>
    <button type="submit" name="verify_otp">Verify OTP</button>
</form>

<?php
if (isset($_POST['verify_otp'])) {

    $otp = trim(mysqli_real_escape_string($conn, $_POST['otp']));
    $email = $_SESSION['reset_email'];

    $check = mysqli_query($conn,
        "SELECT * FROM users
         WHERE email='$email'
         AND otp='$otp'
         AND otp_expiry > NOW()"
    );

    if (mysqli_num_rows($check) == 1) {
        header("Location: reset_password.php");
        exit();
    } else {
        echo "<p class='msg-error'>Invalid or Expired OTP</p>";
    }
}

?>

</div>
</body>
</html>
