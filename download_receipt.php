<?php
session_start();
require('fpdf/fpdf.php');
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    die("Invalid request: Missing order ID");
}

$user_id  = (int) $_SESSION['user_id'];
$order_id = (int) $_GET['order_id'];

/* Fetch order and customer details */
$order_q = mysqli_query($conn, "
    SELECT 
        o.id,
        o.total_amount,
        o.delivery_address,
        o.phone,
        o.order_date,
        o.status,
        o.payment_method,
        o.payment_status,
        u.name AS customer_name,
        u.email AS customer_email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = $order_id AND o.user_id = $user_id
");

if (!$order_q || mysqli_num_rows($order_q) == 0) {
    die("Order not found or access denied.");
}

$order = mysqli_fetch_assoc($order_q);

/* Fetch items if order_items has records */
$items_q = mysqli_query($conn, "
    SELECT 
        oi.price,
        oi.quantity,
        (oi.price * oi.quantity) AS subtotal,
        p.name AS plant_name
    FROM order_items oi
    JOIN plants p ON oi.plant_id = p.id
    WHERE oi.order_id = $order_id
");

/* Fetch transaction ID if available in payments table */
$pay_q = mysqli_query($conn, "
    SELECT transaction_id, payment_date, payment_method, status
    FROM payments
    WHERE order_id = $order_id
    ORDER BY id DESC LIMIT 1
");
$payment = ($pay_q && mysqli_num_rows($pay_q) > 0) ? mysqli_fetch_assoc($pay_q) : null;

/* Generate PDF Invoice */
class PDFReceipt extends FPDF {
    function Header() {
        // Header background
        $this->SetFillColor(46, 125, 50); // Dark Green
        $this->Rect(0, 0, 210, 32, 'F');
        
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 18);
        $this->SetXY(14, 8);
        $this->Cell(0, 10, 'ONLINE PLANT NURSERY', 0, 1, 'L');
        
        $this->SetFont('Arial', '', 10);
        $this->SetX(14);
        $this->Cell(0, 6, 'Official Transaction & Order Receipt', 0, 1, 'L');
        $this->Ln(12);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 5, 'Thank you for growing green with Online Plant Nursery! For support, visit Help Center.', 0, 1, 'C');
        $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDFReceipt();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(34, 34, 34);

// Order & Customer Summary Section
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(46, 125, 50);
$pdf->Cell(0, 8, 'TAX INVOICE / PAYMENT RECEIPT', 0, 1, 'L');
$pdf->SetDrawColor(200, 230, 201);
$pdf->Line(14, $pdf->GetY(), 196, $pdf->GetY());
$pdf->Ln(4);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(50, 50, 50);

// Two column layout: Left = Customer, Right = Order Info
$currY = $pdf->GetY();

// Left Box - Customer Details
$pdf->SetXY(14, $currY);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(90, 6, 'Billed To:', 0, 1);
$pdf->SetFont('Arial', '', 9.5);
$pdf->Cell(90, 5, 'Name: ' . $order['customer_name'], 0, 1);
$pdf->Cell(90, 5, 'Email: ' . $order['customer_email'], 0, 1);
$pdf->Cell(90, 5, 'Phone: ' . ($order['phone'] ? $order['phone'] : 'N/A'), 0, 1);
$pdf->MultiCell(90, 5, 'Address: ' . $order['delivery_address']);

$endLeftY = $pdf->GetY();

// Right Box - Order Details
$pdf->SetXY(110, $currY);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(86, 6, 'Order & Payment Details:', 0, 1);
$pdf->SetFont('Arial', '', 9.5);
$pdf->SetX(110);
$pdf->Cell(86, 5, 'Order ID: #' . $order['id'], 0, 1);
$pdf->SetX(110);
$pdf->Cell(86, 5, 'Date: ' . date('d M Y, h:i A', strtotime($order['order_date'])), 0, 1);
$pdf->SetX(110);
$pdf->Cell(86, 5, 'Payment Method: ' . ($order['payment_method'] ? $order['payment_method'] : 'COD'), 0, 1);
$pdf->SetX(110);
$pdf->Cell(86, 5, 'Payment Status: ' . strtoupper($order['payment_status'] ? $order['payment_status'] : 'Pending'), 0, 1);
if ($payment && !empty($payment['transaction_id'])) {
    $pdf->SetX(110);
    $pdf->Cell(86, 5, 'Transaction ID: ' . $payment['transaction_id'], 0, 1);
}
$pdf->SetX(110);
$pdf->Cell(86, 5, 'Order Status: ' . $order['status'], 0, 1);

$endRightY = $pdf->GetY();
$pdf->SetY(max($endLeftY, $endRightY) + 8);

// Items Table Header
$pdf->SetFillColor(76, 175, 80);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(95, 8, ' Item Description', 1, 0, 'L', true);
$pdf->Cell(30, 8, 'Unit Price', 1, 0, 'R', true);
$pdf->Cell(25, 8, 'Qty', 1, 0, 'C', true);
$pdf->Cell(32, 8, 'Amount (INR)', 1, 1, 'R', true);

// Items Content
$pdf->SetTextColor(40, 40, 40);
$pdf->SetFont('Arial', '', 9.5);
$has_items = false;

if ($items_q && mysqli_num_rows($items_q) > 0) {
    while ($item = mysqli_fetch_assoc($items_q)) {
        $has_items = true;
        $pdf->Cell(95, 7, ' ' . $item['plant_name'], 1, 0, 'L');
        $pdf->Cell(30, 7, 'Rs. ' . number_format($item['price'], 2), 1, 0, 'R');
        $pdf->Cell(25, 7, $item['quantity'], 1, 0, 'C');
        $pdf->Cell(32, 7, 'Rs. ' . number_format($item['subtotal'], 2), 1, 1, 'R');
    }
}

if (!$has_items) {
    // Fallback row if order_items was not populated for older orders
    $pdf->Cell(95, 7, ' Plant Purchase (Order #' . $order['id'] . ')', 1, 0, 'L');
    $pdf->Cell(30, 7, 'Rs. ' . number_format($order['total_amount'], 2), 1, 0, 'R');
    $pdf->Cell(25, 7, '1', 1, 0, 'C');
    $pdf->Cell(32, 7, 'Rs. ' . number_format($order['total_amount'], 2), 1, 1, 'R');
}

// Total summary
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(150, 8, 'Grand Total Paid: ', 1, 0, 'R');
$pdf->SetTextColor(46, 125, 50);
$pdf->Cell(32, 8, 'Rs. ' . number_format($order['total_amount'], 2), 1, 1, 'R');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->MultiCell(0, 5, 'Note: This is a computer generated invoice and does not require a physical signature. All plants are subject to nursery warranty under standard terms.');

// Download file
$filename = 'Receipt_Order_' . $order['id'] . '.pdf';
$pdf->Output('D', $filename);
exit();
?>
