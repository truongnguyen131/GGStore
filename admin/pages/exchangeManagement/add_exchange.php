<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'save') {

        $quantity = $_POST['quantity'];
        $price = ($_POST['price'] == "") ? null : $_POST['price'];
        $status = $_POST['status'];
        $product_id = $_POST['product_id'];
        $user_id = $_POST['user_id'];


        $query = "INSERT INTO `purchased_products`(`customer_id`, `product_id`, `quantity`, `price`, `status`) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed");
        }

        $stmt->bind_param("iiiis", $user_id, $product_id, $quantity, $price, $status);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        createNotification("Add Exchange Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='add_exchange.php';</script>";


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
                ADD A NEW EXCHANGE
            </h4>
            <table>
                <tr>
                    <td>
                        Product name
                    </td>
                    <td>
                        <select name="product_id" id="product_id" aria-controls="dataTable" class="form-control input-left">
                            <?php
                            $sql_product = "SELECT * FROM products";
                            $result_product = $conn->query($sql_product);
                            while ($row_product = $result_product->fetch_assoc()) { ?>
                                <option value="<?php echo $row_product["id"]; ?>">
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
                                <option value="<?php echo $row_user["id"]; ?>">
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
                        <input type="number" min="1" max="20" class="form-control input-left" name="quantity">
                    </td>
                </tr>
                <tr>
                    <td>
                        Price
                    </td>
                    <td>
                        <input type="number" class="form-control input-left" name="price">
                    </td>
                </tr>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <select class="form-control input-left" name="status">
                            <option value="trading">Trading</option>
                            <option value="not trading">Not Trading</option>
                            <option value="review">Review</option>
                            <option value="traded">Traded</option>
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
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
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


            document.frmAddUser.action = "add_exchange.php?task=save";
            document.frmAddUser.submit();

        }

        function goback() {
            location.href = "exchanges_management.php";
        }
    </script>

    <?php
}

?>