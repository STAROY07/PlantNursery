<?php
session_start();
include("db_connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

/* 🔒 CHECK ADMIN LOGIN */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM contact_support WHERE id=$id");

if (!$result || $result->num_rows == 0) {
    die("Message not found");
}

$row = $result->fetch_assoc();

$message_sent = "";

if (isset($_POST['send_reply'])) {

    $reply_text = trim($_POST['reply']);

    if ($reply_text != "") {

        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // 🔴 CHANGE THESE TWO LINES ONLY IF EMAIL CHANGES
            $mail->Username = 'vivekpatil222005@gmail.com';
            $mail->Password = 'bvmlbwknzgzqmozr';

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Email details
            $mail->setFrom('yourgmail@gmail.com', 'Online Plant Nursery Support');
            $mail->addAddress($row['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Reply from Online Plant Nursery Support';
            $mail->Body = nl2br(htmlspecialchars($reply_text));

            $mail->send();
            $message_sent = "Reply sent successfully to user.";

        } catch (Exception $e) {
            $message_sent = "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Reply Support</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}
body{
    margin:0;
    padding:0;
    background:#eef6ee;
}
.reply-wrapper{
    max-width:800px;
    margin:50px auto;
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}
.reply-header{
    font-size:26px;
    font-weight:600;
    color:#2e7d32;
    margin-bottom:25px;
    text-align:center;
}
.user-box{
    background:#f1f8f4;
    border-left:5px solid #2e7d32;
    padding:20px;
    border-radius:8px;
    margin-bottom:25px;
}
.user-box p{
    margin:8px 0;
    color:#333;
    font-size:15px;
}
.reply-box{
    background:#f9fbff;
    border-left:5px solid #1976d2;
    padding:20px;
    border-radius:8px;
}
.reply-box h3{
    margin-top:0;
    color:#1976d2;
}
.reply-box textarea{
    width:100%;
    height:150px;
    padding:12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:15px;
    resize:none;
}
.reply-box textarea:focus{
    outline:none;
    border-color:#1976d2;
}
.send-btn{
    margin-top:15px;
    width:100%;
    padding:14px;
    background:#1976d2;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    font-weight:600;
}
.send-btn:hover{
    background:#0d47a1;
}
.success-msg{
    background:#e6f4ea;
    color:#2e7d32;
    padding:12px;
    border-radius:6px;
    margin-bottom:20px;
    text-align:center;
    font-weight:600;
}
.back-link{
    display:block;
    text-align:center;
    margin-top:25px;
    text-decoration:none;
    color:#2e7d32;
    font-weight:600;
}
.back-link:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="reply-wrapper">

    <div class="reply-header">
        📨 Reply to User
    </div>

    <?php if ($message_sent) { ?>
        <div class="success-msg"><?= $message_sent; ?></div>
    <?php } ?>

    <div class="user-box">
        <p><b>User Name:</b> <?= htmlspecialchars($row['name']); ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($row['email']); ?></p>
        <p><b>Issue:</b> <?= nl2br(htmlspecialchars($row['message'])); ?></p>
    </div>

    <div class="reply-box">
        <h3>Admin Response</h3>

        <form method="post">
            <textarea name="reply" placeholder="Write your solution clearly..." required></textarea>
            <button type="submit" name="send_reply" class="send-btn">
                Send Reply
            </button>
        </form>
    </div>

    <a href="manage_contact_support.php" class="back-link">
        ← Back to Support Messages
    </a>

</div>

</body>
</html>
