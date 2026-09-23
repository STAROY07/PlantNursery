<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();
include 'db_connect.php';

/* 🔐 SECURITY CHECK */
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['payment_pending']) ||
    !isset($_SESSION['order_id'])
) {
    header("Location: cart.php");
    exit();
}

$user_id  = intval($_SESSION['user_id']);
$order_id = intval($_SESSION['order_id']);


/* 🔹 CHECK IF COD */
if (!isset($_GET['method']) || $_GET['method'] !== 'COD') {
    header("Location: cart.php");
    exit();
}

$payment_method = "COD";

/* 🔹 FETCH CART ITEMS */
$cart_q = mysqli_query($conn, "
    SELECT c.plant_id, c.quantity, p.price, p.name, p.image
    FROM cart c
    JOIN plants p ON c.plant_id = p.id
    WHERE c.user_id = $user_id
");

if (mysqli_num_rows($cart_q) == 0) {
    die("Cart is empty");
}

$total = 0;
$cart_items = [];
$receipt = "Order Receipt\n\n";

while ($row = mysqli_fetch_assoc($cart_q)) {
    $cart_items[] = $row;
    $line_total = $row['price'] * $row['quantity'];
    $receipt .= $row['name']." x ".$row['quantity']." = ₹".$line_total."\n";
    $total += $line_total;
}

$receipt .= "\nTotal Amount: ₹$total\n";
$receipt .= "Payment Method: COD\n";

/* 🔹 UPDATE EXISTING ORDER */
mysqli_query($conn, "
    UPDATE orders SET
        total_amount = $total,
        payment_method = 'COD',
        payment_status = 'Pending',
        status = 'Order Placed'
    WHERE id = $order_id
");

/* 🔹 INSERT ORDER ITEMS */
foreach ($cart_items as $item) {
    mysqli_query($conn, "
        INSERT INTO order_items
        (order_id, plant_id, plant_name, price, quantity, image)
        VALUES (
            $order_id,
            {$item['plant_id']},
            '{$item['name']}',
            {$item['price']},
            {$item['quantity']},
            '{$item['image']}'
        )
    ");
}

/* 🔹 INSERT PAYMENT RECORD */
mysqli_query($conn, "
    INSERT INTO payments
    (order_id, amount, payment_method, payment_date, status)
    VALUES (
        $order_id,
        $total,
        'COD',
        NOW(),
        'Pending'
    )
");

/* 🔹 CLEAR CART */
mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

/* 🔹 FETCH USER EMAIL */
$user_q = mysqli_query($conn, "SELECT email FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_q);
$user_email = $user['email'];

/* 🔹 SEND EMAIL (UNCHANGED MAILER) */
$_SESSION['mail_status'] = 'not_sent';
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
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

    $mail->setFrom('vivekpatil222005@gmail.com', 'Plant Nursery');
    $mail->addAddress($user_email);

    $mail->Subject = "Order Confirmation - Order #$order_id";
    $mail->Body    = $receipt;

    $mail->send();
    $_SESSION['mail_status'] = 'sent';

} catch (Exception $e) {
    $_SESSION['mail_status'] = 'failed';
}

/* 🔹 CLEAN SESSION */
unset($_SESSION['payment_pending']);
unset($_SESSION['order_id']);

/* 🔹 REDIRECT */
header("Location: order_success.php");
exit();
?>
