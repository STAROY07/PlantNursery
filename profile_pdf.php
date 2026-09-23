<?php
session_start();
require('fpdf/fpdf.php');
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch user data */
$result = mysqli_query($conn,
"SELECT name, email, phone, address, created_at 
 FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

/* Create PDF */
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

/* Title */
$pdf->Cell(0,10,'User Profile',0,1,'C');
$pdf->Ln(10);

/* Content */
$pdf->SetFont('Arial','',12);

$pdf->Cell(50,10,'Name:',0,0);
$pdf->Cell(0,10,$user['name'],0,1);

$pdf->Cell(50,10,'Email:',0,0);
$pdf->Cell(0,10,$user['email'],0,1);

$pdf->Cell(50,10,'Mobile:',0,0);
$pdf->Cell(0,10,$user['phone'],0,1);

$pdf->Cell(50,10,'Address:',0,1);
$pdf->MultiCell(0,10,$user['address']);

$pdf->Ln(5);

$pdf->Cell(50,10,'Account Created:',0,0);
$pdf->Cell(0,10,$user['created_at'],0,1);

/* Output */
$pdf->Output('D', 'My_Profile.pdf');
?>
