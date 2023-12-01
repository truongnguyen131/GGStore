<?php
session_start();
include_once('../mod/database_connection.php');

$voucher_id = $_POST['voucher_id'];
$freeship_id = $_POST['freeship_id'];
$subtotal = array_sum(array_map(function ($item) {
    return $item['price'] * $item['quantity'];
}, $_SESSION['shopping_cart']));

if ($freeship_id != "none") {
    $sql = "SELECT * FROM `vouchers` WHERE id = $freeship_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $total_price = $subtotal - $row['value'];
    $subtotal = $total_price;
}
if ($voucher_id != "none") {

    $sql = "SELECT * FROM `vouchers` WHERE id = $voucher_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['type'] == "percent") {
        $total_price = $subtotal - ($subtotal * $row['value'] / 100);
    } else {
        $total_price = $subtotal - $row['value'];
    }
    $subtotal = $total_price;
}
if ($voucher_id == "none" && $freeship_id == "none") {
    $total_price = $subtotal;
}

echo round($total_price);

?>