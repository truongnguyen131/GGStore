<?php
session_start();
include_once('../mod/database_connection.php');

$quantity = $_POST['quantity'];
$id_pp = $_POST['id_pp'];

$sql_download = "SELECT pp.*,p.product_name,p.source FROM `purchased_products` pp JOIN products p ON p.id = pp.product_id WHERE pp.id = $id_pp";
$result_download = $conn->query($sql_download);
$row_download = $result_download->fetch_assoc(); ?>

<?php if ($row_download['quantity'] == $quantity) {
    $sql_delete_pp = "DELETE FROM `purchased_products` WHERE id = $id_pp";
    $conn->query($sql_delete_pp);
} ?>

<?php if ($row_download['quantity'] > $quantity) {
    $quantity_update = $row_download['quantity'] - $quantity;
    $sql_update_pp = "UPDATE `purchased_products` SET `quantity`= $quantity_update WHERE id = $id_pp";
    $conn->query($sql_update_pp);
} ?>

<?php

$fileName = $row_download['source'];

echo "<li>";
for ($i = 1; $i <= $quantity; $i++) {
    $downloadFileName = $row_download['product_name'] . "($i)" . ".zip"; ?>

    <a download="<?= $downloadFileName ?>" id="tag_A_<?= $i ?>" href="./uploads/<?= $fileName ?>">
        <?= $downloadFileName ?>
    </a>

<?php }
echo "</li>"; ?>

<script>
    <?php
    for ($i = 1; $i <= $quantity; $i++) { ?>
        document.getElementById('tag_A_<?= $i ?>').click();
    <?php } ?>

    location.href = "bag";
</script>