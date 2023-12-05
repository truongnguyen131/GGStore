<?php
session_start();
include_once('../mod/database_connection.php');
$user_id = $_SESSION['id_account'];
$order_id = $_POST['order_id'];

$sql_order = "SELECT * FROM `orders` WHERE id = $order_id";
$result_order = $conn->query($sql_order);
$row_order = $result_order->fetch_assoc();


?>

<div class="title">
    <h4>Transaction Receipt</h4>
</div>
<div class="saler">
    <span>Buyer:&nbsp;
        <?= $_SESSION['userName'] ?>
    </span>
</div>

<div class="product">
    <span>Order date:&nbsp;
        <?= $row_order['order_date'] ?>
    </span>
</div>

<?php if ($row_order['address'] != "" && $row_order['address'] != NULL) { ?>
    <div class="product">
        <span>Address:&nbsp;
            <?= $row_order['address'] ?>
        </span>
    </div>
<?php } ?>

<div class="amout">
    <span>Status:&nbsp;
        <?= $row_order['status'] ?>
    </span>
</div>

<?php
$sql_sl_ops = "SELECT p.product_name,od.quantity FROM `order_details` od 
JOIN orders o ON o.id = od.order_id 
JOIN products p ON od.product_id = p.id
WHERE o.id = $order_id";
$result_sl_ops = $conn->query($sql_sl_ops);
$count = 1;
while ($row_sl_ops = $result_sl_ops->fetch_assoc()) { ?>
    <div class="product">
        <span>Product
            <?= $count ?>: &nbsp;
            <?= $row_sl_ops['product_name'] ?> x
            <?= $row_sl_ops['quantity'] ?>
        </span>
    </div>
    <?php $count++;
} ?>

<?php
$sql_use_voucher = "SELECT v.value,v.type,v.minimum_condition FROM `voucher_usage` vu 
JOIN vouchers v ON v.id = vu.voucher_id WHERE vu.order_id = $order_id";

$result_use_voucher = $conn->query($sql_use_voucher);
$count = 1;
while ($row_use_voucher = $result_use_voucher->fetch_assoc()) { ?>
    <div class="product">
        <span>Voucher
            <?= $count ?>: &nbsp;
            Discount
            <?= $row_use_voucher['value'] ?>
            <?= ($row_use_voucher['type'] == "percent" ? "%" : "Gcoin") ?>
            for minimum order
            <?= $row_use_voucher['minimum_condition'] ?> Gcoin
        </span>
    </div>
    <?php $count++;
} ?>


<div class="price_sale_product">
    <span>Total price:&nbsp;
        <?= $row_order['total_amount'] ?><i class="fas fa-gem"></i>
    </span>
</div>
<div class="nk-gap"></div>
<div class="sale_accept__btn">
    <?php if ($row_order['status'] == "Waiting for confirmation") {
        echo '<button onclick="cancel_order('.$order_id.')">Order Cancellation</button>';
    } ?>
    <button onclick="close_dialog()">Close Tab</button>
</div>