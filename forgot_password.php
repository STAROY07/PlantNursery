<?php
session_start();
include 'db_connect.php';
date_default_timezone_set('Asia/Kolkata');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password - Plant Nursery</title>

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
.forgot-box{
    background: rgba(255,255,255,0.95);
    width: 360px;
    padding: 35px;
    border-radius: 10px;
    box-shadow: 0 0 18px rgba(34,139,34,0.4);
}
.forgot-box h2{
    text-align: center;
    color: #2E8B57;
    margin-bottom: 20px;
}
.forgot-box input[type="email"]{
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 15px;
}
.forgot-box button{
    width: 100%;
    padding: 12px;
    background-color: #2E8B57;
    color: #fff;
    border: none;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
}
.forgot-box button:hover{
    background-color: #246b45;
}
.msg-error{
    color: red;
    text-align: center;
    margin-top: 10px;
}
.msg-success{
    color: green;
    text-align: center;
    margin-top: 10px;
}
.back-link{
    text-align: center;
    margin-top: 15px;
}
.back-link a{
    color: #2E8B57;
    text-decoration: none;
    font-size: 14px;
}
.back-link a:hover{
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="forgot-box">
<h2>Forgot Password</h2>

<form method="post">
    <input type="email" name="email" placeholder="Enter registered email" required>
    <button type="submit" name="send_otp">Send OTP</button>
</form>

<?php
if (isset($_POST['send_otp'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) == 1) {

        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        mysqli_query($conn,
            "UPDATE users SET otp='$otp', otp_expiry='$expiry' WHERE email='$email'"
        );

        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vivekpatil222005@gmail.com';       // 🔴 change
            $mail->Password = 'bvmlbwknzgzqmozr';// 🔴 change
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yourgmail@gmail.com','Plant Nursery');
            $mail->addAddress($email);

            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "Your OTP is: $otp\nValid for 5 minutes.";

            $mail->send();

            $_SESSION['reset_email'] = $email;
            header("Location: verify_otp.php");
            exit();

        } catch (Exception $e){
            echo "<p class='msg-error'>OTP sending failed</p>";
        }

    } else {
        echo "<p class='msg-error'>Email not registered</p>";
    }
}
?>

<div class="back-link">
    <a href="login.php">← Back to Login</a>
</div>

</div>
</body>
</html>
