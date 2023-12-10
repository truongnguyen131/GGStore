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

if ($type_report == "all" || $type_report == "order") {

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

    if ($result_order->num_rows > 0) {
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
        $sheet_order->getStyle('F7')->applyFromArray($style_boder);
        $sheet_order->setCellValue('G7', 'Customer');
        $sheet_order->getStyle('G7')->applyFromArray($style_boder);
        $sheet_order->setCellValue('H7', 'Date Time');
        $sheet_order->getStyle('H7')->applyFromArray($style_boder);
        $sheet_order->setCellValue('I7', 'Total Amount');
        $sheet_order->getStyle('I7')->applyFromArray($style_boder);
        $sheet_order->setCellValue('J7', 'Status');
        $sheet_order->getStyle('J7')->applyFromArray($style_boder);

        $sheet_order->getStyle('F7:J7')->getFont()->setBold(true);
        $sheet_order->getStyle('F7:J7')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('096D2F');


        $row = 8;
        while ($row_order = $result_order->fetch_assoc()) {
            $sheet_order->setCellValue('F' . $row, "O.No_" . $row_order['id']);
            $sheet_order->getStyle('F' . $row)->applyFromArray($style_boder);
            $sheet_order->setCellValue('G' . $row, $row_order['username']);
            $sheet_order->getStyle('G' . $row)->applyFromArray($style_boder);
            $sheet_order->setCellValue('H' . $row, $row_order['order_date']);
            $sheet_order->getStyle('H' . $row)->applyFromArray($style_boder);
            $sheet_order->setCellValue('I' . $row, $row_order['total_amount']);
            $sheet_order->getStyle('I' . $row)->applyFromArray($style_boder);
            $sheet_order->setCellValue('J' . $row, $row_order['status']);
            $sheet_order->getStyle('J' . $row)->applyFromArray($style_boder);
            $row++;
        }


        // Order Detail Report Sheet
        $sheet_order_detail = $spreadsheet->createSheet();
        $sheet_order_detail->setTitle("Order Detail Report");

        $sheet_order_detail->mergeCells('A2:E2');
        $sheet_order_detail->setCellValue('A2', 'Zone II, 3/2 Street, Can Tho, 90000 Can Tho City');
        $sheet_order_detail->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_order_detail->mergeCells('A3:E3');
        $sheet_order_detail->setCellValue('A3', 'Report No.119');
        $sheet_order_detail->getStyle('A3:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_order_detail->mergeCells('A4:E4');
        $sheet_order_detail->setCellValue('A4', $revenue_date);
        $sheet_order_detail->getStyle('A4:E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_order_detail->mergeCells('F5:K5');
        $sheet_order_detail->setCellValue('F5', 'Revenue Order Detail Report');
        $sheet_order_detail->getStyle('F5:K5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet_order_detail->getStyle('F5:K5')->getFont()->setBold(true);
        $sheet_order_detail->getStyle('F5:K5')->getFont()->setSize(20);

        $sheet_order_detail->getColumnDimension('F')->setWidth(15);
        $sheet_order_detail->getColumnDimension('G')->setWidth(15);
        $sheet_order_detail->getColumnDimension('H')->setWidth(30);
        $sheet_order_detail->getColumnDimension('I')->setWidth(15);
        $sheet_order_detail->getColumnDimension('J')->setWidth(10);
        $sheet_order_detail->getColumnDimension('K')->setWidth(10);

        $sheet_order_detail->setCellValue('F7', 'Order Code');
        $sheet_order_detail->getStyle('F7')->applyFromArray($style_boder);
        $sheet_order_detail->setCellValue('G7', 'Product Code');
        $sheet_order_detail->getStyle('G7')->applyFromArray($style_boder);
        $sheet_order_detail->setCellValue('H7', 'Product Name');
        $sheet_order_detail->getStyle('H7')->applyFromArray($style_boder);
        $sheet_order_detail->setCellValue('I7', 'Unit Price');
        $sheet_order_detail->getStyle('I7')->applyFromArray($style_boder);
        $sheet_order_detail->setCellValue('J7', 'Quantity');
        $sheet_order_detail->getStyle('J7')->applyFromArray($style_boder);
        $sheet_order_detail->setCellValue('K7', 'Total');
        $sheet_order_detail->getStyle('K7')->applyFromArray($style_boder);

        $sheet_order_detail->getStyle('F7:K7')->getFont()->setBold(true);
        $sheet_order_detail->getStyle('F7:K7')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('096D2F');

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
        $row = 8;
        while ($row_od = $result_order_detail->fetch_assoc()) {
            $sheet_order_detail->setCellValue('F' . $row, "O.No_" . $row_od['id']);
            $sheet_order_detail->getStyle('F' . $row)->applyFromArray($style_boder);
            $sheet_order_detail->setCellValue('G' . $row, "P.Code_" . $row_od['product_id']);
            $sheet_order_detail->getStyle('G' . $row)->applyFromArray($style_boder);
            $sheet_order_detail->setCellValue('H' . $row, $row_od['product_name']);
            $sheet_order_detail->getStyle('H' . $row)->applyFromArray($style_boder);
            $sheet_order_detail->setCellValue('I' . $row, $row_od['price']);
            $sheet_order_detail->getStyle('I' . $row)->applyFromArray($style_boder);
            $sheet_order_detail->setCellValue('J' . $row, $row_od['quantity']);
            $sheet_order_detail->getStyle('J' . $row)->applyFromArray($style_boder);
            $sheet_order_detail->setCellValue('K' . $row, ($row_od['price'] * $row_od['quantity']));
            $sheet_order_detail->getStyle('K' . $row)->applyFromArray($style_boder);
            $row++;
        }
    } else {
        $sheet_order = $spreadsheet->createSheet();
        $sheet_order->setTitle("Order Report");

        $sheet_order->setCellValue('A1', 'No data orders available....');
    }
}

if ($type_report == "all" || $type_report == "recharge") {
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
    if ($result_recharge_detail->num_rows > 0) {
        // Recharge Report Sheet
        $sheet_recharge = $spreadsheet->createSheet();
        $sheet_recharge->setTitle("Recharge Report");

        $sheet_recharge->mergeCells('A2:E2');
        $sheet_recharge->setCellValue('A2', 'Zone II, 3/2 Street, Can Tho, 90000 Can Tho City');
        $sheet_recharge->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_recharge->mergeCells('A3:E3');
        $sheet_recharge->setCellValue('A3', 'Report No.119');
        $sheet_recharge->getStyle('A3:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_recharge->mergeCells('A4:E4');
        $sheet_recharge->setCellValue('A4', $revenue_date);
        $sheet_recharge->getStyle('A4:E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_recharge->mergeCells('F5:J5');
        $sheet_recharge->setCellValue('F5', 'Revenue Recharge Report');
        $sheet_recharge->getStyle('F5:J5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet_recharge->getStyle('F5:J5')->getFont()->setBold(true);
        $sheet_recharge->getStyle('F5:J5')->getFont()->setSize(20);

        $sheet_recharge->getColumnDimension('F')->setWidth(20);
        $sheet_recharge->getColumnDimension('G')->setWidth(20);
        $sheet_recharge->getColumnDimension('H')->setWidth(30);
        $sheet_recharge->getColumnDimension('I')->setWidth(10);
        $sheet_recharge->getColumnDimension('J')->setWidth(10);

        $sheet_recharge->setCellValue('F7', 'Recharge Code ');
        $sheet_recharge->getStyle('F7')->applyFromArray($style_boder);
        $sheet_recharge->setCellValue('G7', 'Customer');
        $sheet_recharge->getStyle('G7')->applyFromArray($style_boder);
        $sheet_recharge->setCellValue('H7', 'Date Time');
        $sheet_recharge->getStyle('H7')->applyFromArray($style_boder);
        $sheet_recharge->setCellValue('I7', 'Value');
        $sheet_recharge->getStyle('I7')->applyFromArray($style_boder);
        $sheet_recharge->setCellValue('J7', 'Bank');
        $sheet_recharge->getStyle('J7')->applyFromArray($style_boder);

        $sheet_recharge->getStyle('F7:J7')->getFont()->setBold(true);
        $sheet_recharge->getStyle('F7:J7')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('096D2F');


        $row = 8;
        while ($row_rh = $result_recharge_detail->fetch_assoc()) {
            $sheet_recharge->setCellValue('F' . $row, "Rechar.No_" . $row_rh['id']);
            $sheet_recharge->getStyle('F' . $row)->applyFromArray($style_boder);
            $sheet_recharge->setCellValue('G' . $row, $row_rh['username']);
            $sheet_recharge->getStyle('G' . $row)->applyFromArray($style_boder);
            $sheet_recharge->setCellValue('H' . $row, $row_rh['date']);
            $sheet_recharge->getStyle('H' . $row)->applyFromArray($style_boder);
            $sheet_recharge->setCellValue('I' . $row, $row_rh['value']);
            $sheet_recharge->getStyle('I' . $row)->applyFromArray($style_boder);
            $sheet_recharge->setCellValue('J' . $row, $row_rh['bank']);
            $sheet_recharge->getStyle('J' . $row)->applyFromArray($style_boder);
            $row++;
        }

    } else {
        $sheet_recharge = $spreadsheet->createSheet();
        $sheet_recharge->setTitle("Recharge Report");
        $sheet_recharge->setCellValue('A1', 'No data recharges available....');
    }
}

if ($type_report == "all" || $type_report == "exchange") {
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
    if ($result_exchange_detail->num_rows > 0) {
        // Trading Report Sheet
        $sheet_trading = $spreadsheet->createSheet();
        $sheet_trading->setTitle("Trading Report");

        $sheet_trading->mergeCells('A2:E2');
        $sheet_trading->setCellValue('A2', 'Zone II, 3/2 Street, Can Tho, 90000 Can Tho City');
        $sheet_trading->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_trading->mergeCells('A3:E3');
        $sheet_trading->setCellValue('A3', 'Report No.119');
        $sheet_trading->getStyle('A3:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_trading->mergeCells('A4:E4');
        $sheet_trading->setCellValue('A4', $revenue_date);
        $sheet_trading->getStyle('A4:E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet_trading->mergeCells('F5:J5');
        $sheet_trading->setCellValue('F5', 'Revenue Traing Report');
        $sheet_trading->getStyle('F5:J5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet_trading->getStyle('F5:J5')->getFont()->setBold(true);
        $sheet_trading->getStyle('F5:J5')->getFont()->setSize(20);

        $sheet_trading->getColumnDimension('F')->setWidth(15);
        $sheet_trading->getColumnDimension('G')->setWidth(15);
        $sheet_trading->getColumnDimension('H')->setWidth(30);
        $sheet_trading->getColumnDimension('I')->setWidth(10);
        $sheet_trading->getColumnDimension('J')->setWidth(25);

        $sheet_trading->setCellValue('F7', 'Seller');
        $sheet_trading->getStyle('F7')->applyFromArray($style_boder);
        $sheet_trading->setCellValue('G7', 'Buyer');
        $sheet_trading->getStyle('G7')->applyFromArray($style_boder);
        $sheet_trading->setCellValue('H7', 'Product Name');
        $sheet_trading->getStyle('H7')->applyFromArray($style_boder);
        $sheet_trading->setCellValue('I7', 'Price');
        $sheet_trading->getStyle('I7')->applyFromArray($style_boder);
        $sheet_trading->setCellValue('J7', 'Date Time');
        $sheet_trading->getStyle('J7')->applyFromArray($style_boder);

        $sheet_trading->getStyle('F7:J7')->getFont()->setBold(true);
        $sheet_trading->getStyle('F7:J7')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('096D2F');


        $row = 8;
        while ($row_th = $result_exchange_detail->fetch_assoc()) {
            $sheet_trading->setCellValue('F' . $row, $row_th['seller']);
            $sheet_trading->getStyle('F' . $row)->applyFromArray($style_boder);
            $sheet_trading->setCellValue('G' . $row, $row_th['buyer']);
            $sheet_trading->getStyle('G' . $row)->applyFromArray($style_boder);
            $sheet_trading->setCellValue('H' . $row, $row_th['product_name'] . " x" . $row_th['quantity']);
            $sheet_trading->getStyle('H' . $row)->applyFromArray($style_boder);
            $sheet_trading->setCellValue('I' . $row, $row_th['price']);
            $sheet_trading->getStyle('I' . $row)->applyFromArray($style_boder);
            $sheet_trading->setCellValue('J' . $row, $row_th['transaction_date']);
            $sheet_trading->getStyle('J' . $row)->applyFromArray($style_boder);
            $row++;
        }

    } else {
        $sheet_trading = $spreadsheet->createSheet();
        $sheet_trading->setTitle("Trading Report");
        $sheet_trading->setCellValue('A1', 'No data exchanges available....');
    }
}


$fileName = "Report_Depot";
$writer = new Xlsx($spreadsheet);
$final_fileName = $fileName . ".xlsx";
header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $final_fileName . '');
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
?>