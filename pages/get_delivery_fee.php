<?php
session_start();
include_once('../mod/database_connection.php');
$province_choose = $_POST['province_choose'];
$delivery_fee = 0;
if ($province_choose != "--province--") {
    $sql_fee = "SELECT fee FROM `delivery_fee` WHERE province = '$province_choose'";
    $result_fee = $conn->query($sql_fee);
    $row_fee = $result_fee->fetch_assoc();
    $delivery_fee = $row_fee['fee'];
}
echo $delivery_fee;

if (isset($_SESSION['buy_now_temp'])) {
    $subtotal = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $_SESSION['buy_now_temp']));
} else {
    $subtotal = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $_SESSION['shopping_cart']));
}



?>
<script>
    document.getElementById("total_price").innerText = <?= $subtotal + $delivery_fee ?>;
</script>