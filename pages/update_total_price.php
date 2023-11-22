<?php
session_start();
include_once('../mod/database_connection.php');

$voucher_id = $_POST['voucher_id'];
$subtotal = array_sum(array_map(function ($item) {
    return $item['price'] * $item['quantity'];
}, $_SESSION['shopping_cart']));

if ($voucher_id != "none") {

    $sql = "SELECT * FROM `vouchers` WHERE id = $voucher_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['type'] == "gcoin") {
        $total_price = $subtotal - $row['value'];
    }

    if ($row['type'] == "percent") {
        $total_price = $subtotal - ($subtotal * $row['value'] / 100);
    }
}else{
    $total_price = $subtotal;
}

echo round($total_price);

?>