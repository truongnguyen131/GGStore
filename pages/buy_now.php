<?php
session_start();
include_once('../mod/database_connection.php');
if (!isset($_SESSION['id_account'])) {
    echo '<script> window.location = "./pages/login.php"; </script>';
} else {
    $product_id = $_POST['product_id'];
    $type = (isset($_POST['type'])) ? $_POST['type'] : "product";

    $_SESSION['buy_now'] = [];

    if ($type == "exchange") {
        $sql = "SELECT pp.id,CONCAT(p.product_name, ' x', pp.quantity) AS product_name,p.image_avt_url,pp.price FROM `purchased_products` pp 
    LEFT JOIN products p ON p.id = pp.product_id WHERE pp.id = $product_id";
    } else {
        $sql = "SELECT *, ( SELECT ROUND(p.price * (1 - d.discount_amount/100))
     FROM discounts d WHERE product_id = $product_id AND start_date <= NOW() 
     AND end_date >= NOW()) as price_new
     FROM `products` p WHERE id = $product_id";
    }
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $_SESSION['buy_now'][] = [
        'product_id' => $product_id,
        'product_name' => $row['product_name'],
        'image_avt_url' => $row['image_avt_url'],
        'price' => (!isset($row['price_new']) || $row['price_new'] == "") ? $row['price'] : $row['price_new'],
        'type' => $type,
        'quantity' => 1
    ];

    echo '<script> window.location = "./checkout"; </script>';
}
?>