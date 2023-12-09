<?php
session_start();
include_once('../../../mod/database_connection.php');
require 'fpdf185/fpdf.php';
$type_report = $_POST['type_report'];
$sl_year = $_POST['sl_year'];
$sl_quarter = $_POST['sl_quarter'];
$sl_month = $_POST['sl_month'];
$sl_date = $_POST['sl_date'];
$revenue_date = "";
if ($sl_quarter != "none") {
    $revenue_date .= "Q." . $sl_quarter. "/";
    if ($sl_quarter == 1) {
        $month_of_quarter = "1,2,3";
    }
    if ($sl_quarter == 2) {
        $month_of_quarter = "4,5,6";
    }
    if ($sl_quarter == 3) {
        $month_of_quarter = "7,8,9";
    }
    if ($sl_quarter == 4) {
        $month_of_quarter = "10,11,12";
    }
} else {
    if ($sl_date != "none") {
        $revenue_date .= $sl_date . "/";
    }
    if ($sl_month != "none") {
        $revenue_date .= $sl_month."/";
    }
}

$revenue_date .= $sl_year;

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
// button back
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(10, 6, $pdf->Image("icon_back.gif", 8, 11.3, 0, 0, "", "export_report.php"), 0, 0);
$pdf->Cell(59, 10, '', 0, 1);

$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(52, 10, '', 0, 0);
$pdf->Cell(59, 5, 'Report Revenue Management', 0, 0);
$pdf->Cell(59, 10, '', 0, 1);


$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(101, 10, 'FROM', 0, 0);
$pdf->Cell(59, 10, '', 0, 0);
$pdf->Cell(59, 10, 'DETAILS', 0, 1);

$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(160, 10, 'Galaxy Game Store', 0, 0);
$pdf->Cell(17, 10, 'Report No.', 0, 0);
$pdf->Cell(34, 10, rand(111, 119), 0, 1);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(160, 3, 'Zone II, 3/2 Street, Can Tho, 90000 Can Tho City', 0, 0);
$pdf->Cell(10, 3, '', 0, 0);
$pdf->Cell(160, 3, $revenue_date, 0, 0);

if ($type_report == "all" || $type_report == "order") {
    $pdf->Cell(50, 5, '', 0, 1);
    // Title table
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 20, 'Order Report', 0, 0, 'C');

    $pdf->Cell(50, 20, '', 0, 1);

    // Heading table
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, 6, 'Order Code', 1, 0, 'C');
    $pdf->Cell(45, 6, 'Customer', 1, 0, 'C');
    $pdf->Cell(45, 6, 'Date Time', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Total Amount', 1, 0, 'C');
    $pdf->Cell(50, 6, 'Status', 1, 1, 'C');

    // Content table
    $sql_sl_orders = "SELECT o.*, u.username FROM orders o
    LEFT JOIN users u ON u.id = o.customer_id
    WHERE YEAR(o.order_date) = $sl_year";
    if ($sl_quarter != "none") {
        $sql_sl_orders .= " AND MONTH(o.order_date) in($month_of_quarter)";
    } else {
        if ($sl_date != "none") {
            $sql_sl_orders .= " AND DAY(o.order_date) = $sl_date";
        }
        if ($sl_month != "none") {
            $sql_sl_orders .= " AND MONTH(o.order_date) = $sl_month";
        }
    }
    $result_order = $conn->query($sql_sl_orders);
    while ($row_order = $result_order->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(25, 6, "O.No_" . $row_order['id'], 1, 0, 'C');
        $pdf->Cell(45, 6, $row_order['username'], 1, 0, 'C');
        $pdf->Cell(45, 6, $row_order['order_date'], 1, 0, 'C');
        $pdf->Cell(30, 6, $row_order['total_amount'], 1, 0, 'C');
        $pdf->Cell(50, 6, $row_order['status'], 1, 1, 'C');
    }


    $pdf->Cell(50, 5, '', 0, 1);
    // Title table
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 20, 'Order Details Report', 0, 0, 'C');

    $pdf->Cell(50, 20, '', 0, 1);

    // Heading table
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, 6, 'Order Code', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Product Code', 1, 0, 'C');
    $pdf->Cell(60, 6, 'Product Name', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Unit Price', 1, 0, 'C');
    $pdf->Cell(20, 6, 'Quantity', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Total', 1, 1, 'C');

    // Content table
    $sql_sl_od = "SELECT od.*,p.product_name,p.price FROM order_details od 
    JOIN orders o ON o.id = od.order_id 
    JOIN products p ON p.id = od.product_id 
    WHERE YEAR(o.order_date) = $sl_year";
    if ($sl_quarter != "none") {
        $sql_sl_od .= " AND MONTH(o.order_date) in($month_of_quarter)";
    } else {
        if ($sl_date != "none") {
            $sql_sl_od .= " AND DAY(o.order_date) = $sl_date";
        }
        if ($sl_month != "none") {
            $sql_sl_od .= " AND MONTH(o.order_date) = $sl_month";
        }
    }
    $result_order_detail = $conn->query($sql_sl_od);
    while ($row_od = $result_order_detail->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(25, 6, "O.No_" . $row_od['order_id'], 1, 0, 'C');
        $pdf->Cell(30, 6, "P.Code_" . $row_od['product_id'], 1, 0, 'C');
        $pdf->Cell(60, 6, $row_od['product_name'], 1, 0, 'C');
        $pdf->Cell(30, 6, $row_od['price'], 1, 0, 'C');
        $pdf->Cell(20, 6, $row_od['quantity'], 1, 0, 'C');
        $pdf->Cell(30, 6, ($row_od['price'] * $row_od['quantity']), 1, 1, 'C');
    }
}

if ($type_report == "all" || $type_report == "recharge") {
    $pdf->Cell(50, 5, '', 0, 1);
    // Title table
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 20, 'Recharge Details Report', 0, 0, 'C');

    $pdf->Cell(50, 20, '', 0, 1);

    // Heading table
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(35, 6, 'Recharge Code', 1, 0, 'C');
    $pdf->Cell(40, 6, 'Customer', 1, 0, 'C');
    $pdf->Cell(60, 6, 'Date Time', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Value', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Bank', 1, 1, 'C');

    // Content table
    $sql_sl_rh = "SELECT rh.*,u.username FROM recharge_history rh JOIN users u ON u.id = rh.customer_id
WHERE YEAR(rh.date) = $sl_year";
    if ($sl_quarter != "none") {
        $sql_sl_rh .= " AND MONTH(rh.date) in($month_of_quarter)";
    } else {
        if ($sl_date != "none") {
            $sql_sl_rh .= " AND DAY(rh.date) = $sl_date";
        }
        if ($sl_month != "none") {
            $sql_sl_rh .= " AND MONTH(rh.date) = $sl_month";
        }
    }
    $result_recharge_detail = $conn->query($sql_sl_rh);
    while ($row_rh = $result_recharge_detail->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(35, 6, "Rechar.No_" . $row_rh['id'], 1, 0, 'C');
        $pdf->Cell(40, 6, $row_rh['username'], 1, 0, 'C');
        $pdf->Cell(60, 6, $row_rh['date'], 1, 0, 'C');
        $pdf->Cell(30, 6, $row_rh['value'], 1, 0, 'C');
        $pdf->Cell(30, 6, $row_rh['bank'], 1, 1, 'C');
    }
}

if ($type_report == "all" || $type_report == "exchange") {
    $pdf->Cell(50, 5, '', 0, 1);
    // Title table
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 20, 'Exchange Details Report', 0, 0, 'C');

    $pdf->Cell(50, 20, '', 0, 1);

    // Heading table
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 6, 'Seller', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Buyer', 1, 0, 'C');
    $pdf->Cell(60, 6, 'Product Name', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Price', 1, 0, 'C');
    $pdf->Cell(45, 6, 'Date Time', 1, 1, 'C');

    // Content table
    $sql_sl_th = "SELECT th.transaction_date, th.price,u.username as seller, us.username as buyer, p.product_name,pp.quantity FROM tradings_history  th 
JOIN purchased_products pp ON th.product_id = pp.id
JOIN products p ON p.id = pp.product_id
JOIN users u ON u.id = pp.customer_id
JOIN users us ON us.id = th.buyer_id
WHERE YEAR(th.transaction_date) = $sl_year";
    if ($sl_quarter != "none") {
        $sql_sl_th .= " AND MONTH(th.transaction_date) in($month_of_quarter)";
    } else {
        if ($sl_date != "none") {
            $sql_sl_th .= " AND DAY(th.transaction_date) = $sl_date";
        }
        if ($sl_month != "none") {
            $sql_sl_th .= " AND MONTH(th.transaction_date) = $sl_month";
        }
    }
    $result_exchange_detail = $conn->query($sql_sl_th);
    while ($row_th = $result_exchange_detail->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 6, $row_th['seller'], 1, 0, 'C');
        $pdf->Cell(30, 6, $row_th['buyer'], 1, 0, 'C');
        $pdf->Cell(60, 6, $row_th['product_name'] . " x" . $row_th['quantity'], 1, 0, 'C');
        $pdf->Cell(30, 6, $row_th['price'], 1, 0, 'C');
        $pdf->Cell(45, 6, $row_th['transaction_date'], 1, 1, 'C');
    }
}




$pdf->Output();

?>