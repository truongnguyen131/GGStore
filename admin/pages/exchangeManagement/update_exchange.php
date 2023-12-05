<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $sql_dID = "SELECT * FROM purchased_products WHERE id = $id";
    $result_dID = $conn->query($sql_dID);
    $row_dID = $result_dID->fetch_assoc();
    $product_id = $row_dID['product_id'];
    $customer_id = $row_dID['customer_id'];
    $quantity = $row_dID['quantity'];
    $price = $row_dID['price'];
    $status = $row_dID['status'];


    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {

        $quantity = $_POST['quantity'];
        $price = ($_POST['price'] == "") ? null : $_POST['price'];
        $status = $_POST['status'];
        $product_id = $_POST['product_id'];
        $user_id = $_POST['user_id'];


        $query = "UPDATE `purchased_products` SET `customer_id`=?,`product_id`=?,`quantity`=?,`price`=?,`status`=? WHERE `id`= ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed");
        }

        $stmt->bind_param("iiiisi", $user_id, $product_id, $quantity, $price, $status, $id);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        createNotification("Update Exchange Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='update_exchange.php?id=$id';</script>";
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
                UPDATE EXCHANGE
            </h4>
            <table>
                <tr>
                    <td>
                        Product name
                    </td>
                    <td>
                        <select name="product_id" id="product_id" aria-controls="dataTable" class="form-control input-left">
                            <?php
                            $sql_product = "SELECT * FROM products WHERE release_date <= NOW()";
                            $result_product = $conn->query($sql_product);
                            while ($row_product = $result_product->fetch_assoc()) { ?>
                                <option value="<?php echo $row_product["id"]; ?>" <?= ($row_product["id"] == $product_id) ? 'selected' : '' ?>>
                                    <?php echo $row_product["product_name"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        User name
                    </td>
                    <td>
                        <select name="user_id" id="user_id" aria-controls="dataTable" class="form-control input-left">
                            <?php
                            $sql_user = "SELECT * FROM users WHERE role = 'user'";
                            $result_user = $conn->query($sql_user);
                            while ($row_user = $result_user->fetch_assoc()) { ?>
                                <option value="<?php echo $row_user["id"]; ?>" <?= ($row_user["id"] == $customer_id) ? 'selected' : '' ?>>
                                    <?php echo $row_user["full_name"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Quantity
                    </td>
                    <td>
                        <input type="number" min="1" max="20" value="<?= $quantity ?>" class="form-control input-left" name="quantity">
                    </td>
                </tr>
                <tr>
                    <td>
                        Price
                    </td>
                    <td>
                        <input type="number" class="form-control input-left" value="<?= $price ?>" name="price">
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <select class="form-control input-left" name="status">
                            <option value="trading" <?= (($status) == 'trading') ? 'selected' : '' ?>>Trading</option>
                            <option value="not trading" <?= (($status) == 'not trading') ? 'selected' : '' ?>>Not Trading</option>
                            <option value="review" <?= (($status) == 'review') ? 'selected' : '' ?>>Review</option>
                            <option value="traded" <?= (($status) == 'traded') ? 'selected' : '' ?>>Traded</option>
                        </select>
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

            if (document.frmAddUser.quantity.value <= 0 || document.frmAddUser.quantity.value >= 21) {
                $('#error').html("Quantity receives only values from 1 to 20!!!")
                document.frmAddUser.quantity.focus();
                return false;
            }

            if (document.frmAddUser.status.value != "not trading") {
                if (document.frmAddUser.price.value <= 0) {
                    $('#error').html("Price invalid!!!")
                    document.frmAddUser.price.focus();
                    return false;
                }
            }


            document.frmAddUser.action = "update_exchange.php?task=update&id=<?= $id ?>";
            document.frmAddUser.submit();

        }

        function goback() {
            location.href = "exchanges_management.php";
        }
    </script>

<?php
}

?>