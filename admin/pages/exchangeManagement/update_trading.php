<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $sql_dID = "SELECT * FROM tradings_history WHERE id = $id";
    $result_dID = $conn->query($sql_dID);
    $row_dID = $result_dID->fetch_assoc();
    $old_product_id = $row_dID['product_id'];
    $buyer_id = $row_dID['buyer_id'];
    $price = $row_dID['price'];
    $transaction_date = $row_dID['transaction_date'];

    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {
        $new_product_id = $_POST['new_product_id'];
        $price = $_POST['price'];
        $buyer_id = $_POST['buyer_id'];
        $transaction_date = $_POST['transaction_date'];

        if ($new_product_id == $old_product_id) {
            $check_query = "SELECT customer_id FROM `purchased_products` WHERE id = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("i", $old_product_id);
            $check_stmt->execute();
            $seller_id = $check_stmt->get_result()->fetch_row()[0];
            if ($seller_id == $buyer_id) {
                createNotification("Users cannot purchase their own items!!!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='update_trading.php?id=$id';</script>";
            } else {
                $query = "UPDATE `tradings_history` SET `buyer_id`=?,`price`=?,`transaction_date`=? WHERE `id`=?";
                $stmt = $conn->prepare($query);

                if (!$stmt) {
                    die("Prepare failed");
                }

                $stmt->bind_param("iisi", $buyer_id, $price, $transaction_date, $id);

                if (!$stmt->execute()) {
                    die("Execute failed: " . $stmt->error);
                }

                $stmt->close();
                $conn->close();
                createNotification("Update Trading Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='update_trading.php?id=$id';</script>";
            }
        } else {
            $sql_update = "UPDATE `purchased_products` SET `status`='trading' WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("i", $old_product_id);
            $stmt_update->execute();
            $stmt_update->close();

            $query_insert = "UPDATE `tradings_history` SET `buyer_id`=?, `product_id`=?,`price`=?,`transaction_date`=? WHERE `id`=?";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("iiisi", $buyer_id, $new_product_id, $price, $transaction_date, $id);

            if ($stmt_insert->execute()) {
                $sql_update = "UPDATE `purchased_products` SET `status`='traded' WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("i", $new_product_id);
                $stmt_update->execute();
                $stmt_update->close();
            }

            $stmt_insert->close();
            $conn->close();
            createNotification("Update Trading Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_trading.php?id=$id';</script>";
        }
    }

?>

    <style>
        .input-left {
            margin-left: 10px;
            margin-bottom: 10px;
        }

        .error {
            padding-left: 20px;
            font-size: 15px;
            color: red;
        }
    </style>

    <div class="container-fluid">
        <form name="frmAddUser" method="post">
            <h4 class="ico_mug">
                UPDATE TRADING
            </h4>
            <table>
                <tr>
                    <td>
                        Product ID
                    </td>
                    <td>
                        <select name="new_product_id" aria-controls="dataTable" class="form-control input-left">
                            <option value="<?php echo $old_product_id; ?>">
                                <?php echo $old_product_id; ?>
                            </option>
                            <?php
                            $sql_product = "SELECT * FROM purchased_products WHERE status = 'trading'";
                            $result_product = $conn->query($sql_product);
                            while ($row_product = $result_product->fetch_assoc()) { ?>
                                <option value="<?php echo $row_product["id"]; ?>">
                                    <?php echo $row_product["id"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Buyer
                    </td>
                    <td>
                        <select name="buyer_id" id="buyer_id" aria-controls="dataTable" class="form-control input-left">
                            <?php
                            $sql_user = "SELECT * FROM users WHERE role = 'user'";
                            $result_user = $conn->query($sql_user);
                            while ($row_user = $result_user->fetch_assoc()) { ?>
                                <option value="<?php echo $row_user["id"]; ?>" <?= ($row_user["id"] == $buyer_id) ? 'selected' : '' ?>>
                                    <?= $row_user["full_name"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Transaction date
                    </td>
                    <td>
                        <input type="datetime-local" value="<?= $transaction_date ?>" class="form-control input-left" name="transaction_date">
                    </td>
                </tr>
                <tr>
                    <td>
                        Price
                    </td>
                    <td>
                        <input type="number" value="<?= $price ?>" class="form-control input-left" name="price">
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td id="error" class="error"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="button" class="btn btn-info input-left" name="btnSave" value="Save" onClick="save()">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack" value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function save() {
            $('#error').html("")

            if (document.frmAddUser.transaction_date.value == "") {
                $('#error').html("Enter transaction date!!!")
                document.frmAddUser.transaction_date.focus();
                return false;
            }


            if (document.frmAddUser.price.value <= 0) {
                $('#error').html("Enter price!!!")
                document.frmAddUser.price.focus();
                return false;
            }

            document.frmAddUser.action = "update_trading.php?task=update&id=<?= $id ?>";
            document.frmAddUser.submit();

        }

        function goback() {
            location.href = "tradings_history.php";
        }
    </script>

<?php
}

?>