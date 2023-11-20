<?php
session_start();
include_once('../mod/database_connection.php');
if (isset($_POST['newQuantity']) && isset($_POST['key'])) {
    $_SESSION['shopping_cart'][$_POST['key']]['quantity'] = $_POST['newQuantity'];
} else {

    $product_id = $_POST['product_id'];

    $sql = "SELECT *, ( SELECT ROUND(p.price * (1 - d.discount_amount/100))
    FROM discounts d WHERE product_id = $product_id AND start_date <= NOW() 
    AND end_date >= NOW()) as price_new
    FROM `products` p WHERE id = $product_id";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!isset($_SESSION['shopping_cart'])) {
        $_SESSION['shopping_cart'] = [];
    }

    $product_ids = array_column($_SESSION['shopping_cart'], 'product_id');

    if (in_array($product_id, $product_ids)) {

        foreach ($_SESSION['shopping_cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity']++;
                break;
            }
        }

    } else {

        $_SESSION['shopping_cart'][] = [
            'product_id' => $product_id,
            'product_name' => $row['product_name'],
            'image_avt_url' => $row['image_avt_url'],
            'price' => $row['price_new'] == "" ? $row['price'] : $row['price_new'],
            'quantity' => 1
        ];

    }

    $total_quantity = array_sum(array_column($_SESSION['shopping_cart'], 'quantity'));
    echo $total_quantity;

    echo '<script>
    notification_dialog("Success", "Add to cart successful!!!");
    </script>
    ';
}
?>