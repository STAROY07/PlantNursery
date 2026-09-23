<?php
session_start();
include 'db_connect.php';
date_default_timezone_set('Asia/Kolkata');

/* Security check */
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if (isset($_POST['reset'])) {

    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {

        $newpass = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn,
            "UPDATE users 
             SET password='$newpass', otp=NULL, otp_expiry=NULL 
             WHERE email='$email'"
        );

        session_destroy();
        header("Location: login.php?reset=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password - Plant Nursery</title>

<style>
body{
    background: url('plants-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
.reset-box{
    background: rgba(255,255,255,0.97);
    width: 360px;
    padding: 35px;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.reset-box h2{
    text-align: center;
    color: #2E8B57;
    margin-bottom: 20px;
}
.reset-box input{
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}
.reset-box button{
    width: 100%;
    padding: 12px;
    background-color: #2E8B57;
    color: #fff;
    border: none;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
}
.reset-box button:hover{
    background-color: #246b45;
}
.msg-error{
    color: red;
    text-align: center;
    margin-top: 10px;
    font-size: 14px;
}
</style>
</head>

<body>

<div class="reset-box">
<h2>Reset Password</h2>

<form method="post">
    <input type="password" name="password" placeholder="New Password" required>
    <br><br>
    <button type="submit" name="reset">Reset Password</button>
</form>

<?php
if (isset($error)) {
    echo "<p class='msg-error'>$error</p>";
}
?>

</div>
</body>
</html>
