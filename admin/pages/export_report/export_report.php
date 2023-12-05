<?php
session_start();
require 'fpdf185/fpdf.php';


$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(52, 10, '', 0, 0);
$pdf->Cell(59, 5, 'Report Depot Management', 0, 0);
$pdf->Cell(59, 10, '', 0, 1);

$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(101, 10, 'WET', 0, 0);
$pdf->Cell(59, 10, '', 0, 0);
$pdf->Cell(59, 10, 'DETAILS', 0, 1);

$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(160, 10, 'NUOC MAM PHU QUOC', 0, 0);
$pdf->Cell(17, 10, 'Report No.', 0, 0);
$pdf->Cell(34, 10, rand(111, 119), 0, 1);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(160, 3, '477/KP8-Nguyen Hue-Duong Dong-Phu Quoc-Kien Giang', 0, 0);
$pdf->Cell(10, 3, 'Date:', 0, 0);
$pdf->Cell(34, 3, date('M/d/Y'), 0, 1);

$pdf->Cell(50, 15, '', 0, 1);
$pdf->SetFont('Arial', 'B', 10);

// Heading table
$pdf->Cell(40, 6, 'Product Code', 1, 0, 'C');
$pdf->Cell(25, 6, 'BI', 1, 0, 'C');
$pdf->Cell(25, 6, 'Stock-in', 1, 0, 'C');
$pdf->Cell(25, 6, 'Stock-out', 1, 0, 'C');
$pdf->Cell(50, 6, 'Datetime', 1, 0, 'C');
$pdf->Cell(25, 6, 'EI', 1, 1, 'C');



$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, "Game", 1, 0, 'C');
$pdf->Cell(25, 6, "11", 1, 0, 'C');

$pdf->Cell(25, 6, '', 1, 0, 'C');
$pdf->Cell(25, 6, "12", 1, 0, 'C');

$pdf->Cell(50, 6, "AAAA", 1, 0, 'C');
$pdf->Cell(25, 6, "AAA", 1, 1, 'C');


$pdf->Cell(50, 15, '', 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 6, $pdf->Image("icon_back.gif", 8, 11.3, 0, 0, "", "../template/dashboard.php"), 0, 0);
$pdf->Output();

?>