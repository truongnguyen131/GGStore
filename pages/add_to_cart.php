<?php
session_start();
include_once('../mod/database_connection.php');
if (!isset($_SESSION['id_account'])) {
    echo '<script> window.location = "./pages/login.php"; </script>';
} else {
    if (isset($_POST['newQuantity']) && isset($_POST['key'])) {
        $_SESSION['shopping_cart'][$_POST['key']]['quantity'] = $_POST['newQuantity'];
    } else {

        $product_id = $_POST['product_id'];
        $type = (isset($_POST['type'])) ? $_POST['type'] : "product";

        if (!isset($_SESSION['shopping_cart'])) {
            $_SESSION['shopping_cart'] = [];
        }


        $check_gear = true;
        if ($type == 'product' || $type == 'exchange') {
            foreach ($_SESSION['shopping_cart'] as $item) {
                if ($item['type'] == 'gear') {
                    $check_gear = false;
                    break;
                }
            }
        }

        if ($type == 'gear') {
            foreach ($_SESSION['shopping_cart'] as $item) {
                if ($item['type'] != 'gear') {
                    $check_gear = false;
                    break;
                }
            }
        }

        if ($check_gear) {

            if ($type == "exchange") {
                $sql = "SELECT pp.id,CONCAT(p.product_name, ' x', pp.quantity) AS product_name,p.image_avt_url,pp.price FROM `purchased_products` pp 
            LEFT JOIN products p ON p.id = pp.product_id WHERE pp.id = $product_id";
            } else {
                $sql = "SELECT *, ( SELECT ROUND(p.price * (1 - d.discount_amount/100))
             FROM discounts d WHERE product_id = $product_id AND start_date <= NOW() 
             AND end_date >= NOW()) as price_new
             FROM `products` p WHERE id = $product_id";
            }

            $hasExchange = false;

            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $product_ids = array_column($_SESSION['shopping_cart'], 'product_id');

            if (in_array($product_id, $product_ids)) {

                foreach ($_SESSION['shopping_cart'] as &$item) {

                    if ($item['product_id'] == $product_id && $item['type'] == "exchange") {
                        $hasExchange = true;
                        break;
                    }

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
                    'price' => (!isset($row['price_new']) || $row['price_new'] == "") ? $row['price'] : $row['price_new'],
                    'type' => $type,
                    'quantity' => 1
                ];

            }
            if (isset($_GET['buy_now'])) {
                echo '<script>
            window.location = "./checkout";
            </script>';
            } else {
                if ($hasExchange) {
                    echo '<script>
                notification_dialog("Failed", "You have already added this product!!!");
                </script>';
                } else {
                    echo '<script>
                notification_dialog("Success", "Add to cart successful!!!");
                </script>';
                }

            }

        } else {
            echo '<script>
                notification_dialog("Failed", "Gear cannot be purchased with the game!!!");
                </script>';
        }

        $total_quantity = array_sum(array_column($_SESSION['shopping_cart'], 'quantity'));
        echo $total_quantity;



    }
}


?>