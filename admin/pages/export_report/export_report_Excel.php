<?php
session_start();
include_once('../../../mod/database_connection.php');
require '../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$type_report = $_POST['type_report'];
$sl_year = $_POST['sl_year'];
$sl_quarter = $_POST['sl_quarter'];
$sl_month = $_POST['sl_month'];
$sl_date = $_POST['sl_date'];
$revenue_date = "";
if ($sl_quarter != "none") {
    $revenue_date .= "Q." . $sl_quarter . "/";
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
        $revenue_date .= $sl_month . "/";
    }
}
$revenue_date .= $sl_year;


$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);

$style_boder = array(
    'borders' => array(
        'outline' => array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => 'FFFF'),
        ),
    ),
);


// Order Report Sheet
$sheet_order = $spreadsheet->createSheet();
$sheet_order->setTitle("Order Report");

$sheet_order->mergeCells('A2:E2');
$sheet_order->setCellValue('A2', 'Zone II, 3/2 Street, Can Tho, 90000 Can Tho City');
$sheet_order->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet_order->mergeCells('A3:E3');
$sheet_order->setCellValue('A3', 'Report No.119');
$sheet_order->getStyle('A3:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet_order->mergeCells('A4:E4');
$sheet_order->setCellValue('A4', $revenue_date);
$sheet_order->getStyle('A4:E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet_order->mergeCells('F5:J5');
$sheet_order->setCellValue('F5', 'Revenue Order Report');
$sheet_order->getStyle('F5:J5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_order->getStyle('F5:J5')->getFont()->setBold(true);
$sheet_order->getStyle('F5:J5')->getFont()->setSize(20);

$sheet_order->getColumnDimension('F')->setWidth(15);
$sheet_order->getColumnDimension('G')->setWidth(20);
$sheet_order->getColumnDimension('H')->setWidth(20);
$sheet_order->getColumnDimension('I')->setWidth(15);
$sheet_order->getColumnDimension('J')->setWidth(25);

$sheet_order->setCellValue('F7', 'Order Code');
$style = $sheet_order->getStyle('F7')->applyFromArray($style_boder);
$sheet_order->setCellValue('G7', 'Customer');
$style = $sheet_order->getStyle('G7')->applyFromArray($style_boder);
$sheet_order->setCellValue('H7', 'Date Time');
$style = $sheet_order->getStyle('H7')->applyFromArray($style_boder);
$sheet_order->setCellValue('I7', 'Total Amount');
$style = $sheet_order->getStyle('I7')->applyFromArray($style_boder);
$sheet_order->setCellValue('J7', 'Status');
$style = $sheet_order->getStyle('J7')->applyFromArray($style_boder);

$sheet_order->getStyle('F7:J7')->getFont()->setBold(true);
$sheet_order->getStyle('F7:J7')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setRGB('096D2F');

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
$row = 8;
while ($row_order = $result_order->fetch_assoc()) {
    $sheet_order->setCellValue('F' . $row, "O.No_" . $row_order['id']);
    $style = $sheet_order->getStyle('F' . $row)->applyFromArray($style_boder);
    $sheet_order->setCellValue('G' . $row, $row_order['username']);
    $style = $sheet_order->getStyle('G' . $row)->applyFromArray($style_boder);
    $sheet_order->setCellValue('H' . $row, $row_order['order_date']);
    $style = $sheet_order->getStyle('H' . $row)->applyFromArray($style_boder);
    $sheet_order->setCellValue('I' . $row, $row_order['total_amount']);
    $style = $sheet_order->getStyle('I' . $row)->applyFromArray($style_boder);
    $sheet_order->setCellValue('J' . $row, $row_order['status']);
    $style = $sheet_order->getStyle('J' . $row)->applyFromArray($style_boder);
    $row++;
}






// Trading Report Sheet
$sheet_trading = $spreadsheet->createSheet();
$sheet_trading->setTitle("Trading Report");


$fileName = "Report_Depot";
$writer = new Xlsx($spreadsheet);
$final_fileName = $fileName . ".xlsx";
header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $final_fileName . '');
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
?>