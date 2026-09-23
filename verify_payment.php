<?php
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('razorpay-php/Razorpay.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();
include 'db_connect.php';

include 'razorpay_config.php';
$keyId = $razorpay_key_id;
$keySecret = $razorpay_key_secret;

$api = new Api($keyId, $keySecret);

if (
    !isset($_SESSION['order_id']) ||
    !isset($_GET['razorpay_payment_id']) ||
    !isset($_GET['razorpay_signature']) ||
    !isset($_GET['razorpay_order_id'])
) {
    header("Location: payment_failed.php");
    exit();
}

$order_id   = $_SESSION['order_id'];
$user_id    = $_SESSION['user_id'];
$payment_id = $_GET['razorpay_payment_id'];
$signature  = $_GET['razorpay_signature'];
$rp_order   = $_GET['razorpay_order_id'];

try {

    $api->utility->verifyPaymentSignature([
        'razorpay_order_id'   => $rp_order,
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature'  => $signature
    ]);

    /* ============================= */
    /* FETCH CART ITEMS */
    /* ============================= */

    $cart_q = mysqli_query($conn, "
        SELECT c.plant_id, c.quantity, p.price, p.name, p.image
        FROM cart c
        JOIN plants p ON c.plant_id = p.id
        WHERE c.user_id = $user_id
    ");

    $total = 0;
    $receipt = "Order Receipt\n\n";

    while ($row = mysqli_fetch_assoc($cart_q)) {

        $line_total = $row['price'] * $row['quantity'];
        $total += $line_total;

        $receipt .= $row['name']." x ".$row['quantity']." = ₹".$line_total."\n";

        mysqli_query($conn, "
            INSERT INTO order_items
            (order_id, plant_id, plant_name, price, quantity, image)
            VALUES (
                $order_id,
                {$row['plant_id']},
                '{$row['name']}',
                {$row['price']},
                {$row['quantity']},
                '{$row['image']}'
            )
        ");
    }

    $receipt .= "\nTotal Amount: ₹$total\n";
    $receipt .= "Payment Method: Razorpay\n";

    /* ============================= */
    /* UPDATE ORDER */
    /* ============================= */

    mysqli_query($conn, "
        UPDATE orders SET
            payment_status = 'paid',
            status = 'Order Placed',
            total_amount = $total,
            payment_method = 'Razorpay'
        WHERE id = $order_id
    ");
    mysqli_query($conn,
    "UPDATE payments 
     SET status='Refunded' 
     WHERE order_id='$order_id'"
);

    
    /* INSERT PAYMENT RECORD */
    
$transaction_id = $payment_id;  // Razorpay payment ID

mysqli_query($conn, "
    INSERT INTO payments
    (order_id, amount, payment_method, payment_date, status, transaction_id)
    VALUES (
        $order_id,
        $total,
        'Razorpay',
        NOW(),
        'Paid',
        '$transaction_id'
    )
");
    /* ============================= */
    /* SEND EMAIL (WITH LOCAL SSL FIX & CATCH) */
    /* ============================= */

    try {
        $user_q = mysqli_query($conn, "SELECT email FROM users WHERE id = $user_id");
        $user = mysqli_fetch_assoc($user_q);

        if ($user && !empty($user['email'])) {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vivekpatil222005@gmail.com';
            $mail->Password = 'bvmlbwknzgzqmozr';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Fix local OpenSSL certificate verify failed in XAMPP
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('vivekpatil222005@gmail.com', 'Plant Nursery');
            $mail->addAddress($user['email']);

            $mail->Subject = "Order Confirmation - Order #$order_id";
            $mail->Body    = $receipt;

            $mail->send();
        }
    } catch (\Exception $mailEx) {
        // Log or silently ignore email failure so order completion is not blocked
        error_log("Email sending error: " . $mailEx->getMessage());
    }

    /* ============================= */
    /* CLEAR CART */
    /* ============================= */

    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

    unset($_SESSION['payment_pending']);
    unset($_SESSION['order_id']);

    header("Location: order_success.php");
    exit();

} catch (SignatureVerificationError $e) {

    mysqli_query($conn, "
        UPDATE orders SET
            payment_status = 'failed',
            status = 'Payment Failed'
        WHERE id = $order_id
    ");

    header("Location: payment_failed.php");
    exit();
}
