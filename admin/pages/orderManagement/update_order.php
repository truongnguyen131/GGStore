<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $sql_dID = "SELECT * FROM orders WHERE id = $id";
    $result_dID = $conn->query($sql_dID);
    $row_dID = $result_dID->fetch_assoc();
    $user_id = $row_dID['customer_id'];
    $order_date = $row_dID['order_date'];
    $total_amount = $row_dID['total_amount'];
    $address = $row_dID['address'];
    $status = $row_dID['status'];

    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {
        $user_id = $_POST['slUser'];
        $order_date = date("Y-m-d H:i:s", strtotime($_POST["order_date"]));
        $total_amount = $_POST['total_amount'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $products = $_POST['products'];
        $quantities = $_POST['quantities'];
        $mergedData = [];



        $sql = "UPDATE `orders` SET
        `customer_id`= ?,`order_date`= ?,`total_amount`= ?,`address`= ?,`status`= ? WHERE id = ?";
        $sql_stmt = $conn->prepare($sql);
        if ($sql_stmt === false) {
            die("Error preparing statement");
        }
        $sql_stmt->bind_param("isissi", $user_id, $order_date, $total_amount, $address, $status, $id);
        if ($sql_stmt->execute()) {
            $sql_delete_order_detail = "DELETE FROM `order_details` WHERE order_id = $id";
            $conn->query($sql_delete_order_detail);

            for ($i = 0; $i < count($products); $i++) {
                $product = $products[$i];
                $quantity = $quantities[$i];

                if (array_key_exists($product, $mergedData)) {
                    $mergedData[$product] += $quantity;
                } else {
                    $mergedData[$product] = $quantity;
                }
            }

            foreach ($mergedData as $product => $quantity) {
                $sql_insert_new_order_detail = "INSERT INTO `order_details`(`order_id`, `product_id`, `quantity`) 
                VALUES ('$id','$product','$quantity')";
                $conn->query($sql_insert_new_order_detail);
            }
            createNotification("Update Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        } else {
            createNotification("An error occurred during the update process! Update Order Failed!!!", "error", date('Y-m-d H:i:s'), "undisplayed");
        }

        $sql_stmt->close();

        echo "<script>location.href='update_order.php?id=$id';</script>";
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
        <form name="frmUpdateUser" method="post">
            <h4 class="ico_mug">
                UPDATE ORDER
            </h4>
            <table>
                <tr>
                    <td>
                        Customer
                    </td>
                    <td>
                        <select class="custom-select custom-select-sm form-control form-control-sm input-left"
                            name="slUser">
                            <?php
                            $sql = "SELECT * FROM users WHERE role = 'user'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { ?>
                                    <option <?php echo ($row["id"] == $user_id) ? 'selected' : ''; ?>
                                        value="<?php echo $row["id"]; ?>">
                                        <?php echo $row["full_name"]; ?>
                                    </option>
                                <?php }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Order date
                    </td>
                    <td>
                        <input type="datetime-local" name="order_date" id="order_date"
                            class="form-control form-control-sm input-left" value="<?php echo $order_date; ?>"
                            placeholder="" aria-controls="dataTable">
                    </td>
                </tr>

                <tr>
                    <td>
                        Total amount
                    </td>
                    <td>
                        <input type="number" name="total_amount" id="total_amount"
                            class="form-control form-control-sm input-left" min="0" value="<?php echo $total_amount; ?>"
                            placeholder="" aria-controls="dataTable">
                    </td>
                </tr>

                <tr>
                    <td>
                        Address
                    </td>
                    <td>
                        <input type="text" name="address" id="address" class="form-control form-control-sm input-left"
                            value="<?php echo $address; ?>" placeholder="" aria-controls="dataTable">
                    </td>
                </tr>

                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <select name="status" id="status" aria-controls="dataTable"
                            class="custom-select custom-select-sm form-control form-control-sm input-left">
                            <option value="Waiting for confirmation" <?php echo (($status) == "Waiting for confirmation") ? 'selected' : ''; ?>>Waiting for confirmation</option>
                            <option value="Waiting for delivery" <?php echo (($status) == "Waiting for delivery") ? 'selected' : ''; ?>>Waiting for delivery</option>
                            <option value="Paid" <?php echo (($status) == "Paid") ? 'selected' : ''; ?>>
                                Paid</option>
                            <option value="Cancelled" <?php echo (($status) == "Cancelled") ? 'selected' : ''; ?>>Cancelled
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Products
                    </td>
                    <td>
                        <div id="order_detail">
                            <?php
                            $sql = "SELECT * FROM order_details WHERE order_id = $id";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) { ?>
                                <div class="row">
                                    <div class="col-md-10">
                                        <select name="products[]" aria-controls="dataTable"
                                            class="custom-select custom-select-sm form-control form-control-sm input-left">
                                            <?php
                                            $sql1 = "SELECT * FROM products WHERE release_date <= NOW()";
                                            $result1 = $conn->query($sql1);
                                            $productData = array();
                                            while ($row1 = $result1->fetch_assoc()) {
                                                $productData[] = $row1; ?>
                                                <option value="<?= $row1['id'] ?>" <?php if ($row1['id'] == $row['product_id'])
                                                      echo "selected"; ?>>
                                                    <?= $row1['product_name'] ?>
                                                </option>
                                            <?php }
                                            $productDataJSON = json_encode($productData); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="quantities[]" min="1" value="<?= $row['quantity'] ?>"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>

                            <?php } ?>
                        </div>
                        <button type="button" onclick="add_sl_genre()" class="btn-circle btn-sm input-left">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" onclick="remove_sl_genre()" class="btn-circle btn-sm">
                            <i class="fas fa-minus"></i>
                        </button>

                        <script>
                            function add_sl_genre() {
                                var productData = <?php echo $productDataJSON; ?>;
                                var selectDiv = document.createElement('div');
                                selectDiv.className = 'row';

                                var selectCol = document.createElement('div');
                                selectCol.className = 'col-md-10';

                                var select = document.createElement('select');
                                select.name = "products[]";
                                select.className = 'custom-select custom-select-sm form-control form-control-sm input-left';

                                for (var i = 0; i < productData.length; i++) {
                                    var option = document.createElement("option");
                                    option.value = productData[i].id;
                                    option.textContent = productData[i].product_name;
                                    select.appendChild(option);
                                }

                                var quantityCol = document.createElement('div');
                                quantityCol.className = 'col-md-2';

                                var quantityInput = document.createElement('input');
                                quantityInput.type = 'number';
                                quantityInput.name = 'quantities[]';
                                quantityInput.min = '1';
                                quantityInput.value = '1';
                                quantityInput.className = 'form-control form-control-sm';

                                selectCol.appendChild(select);
                                quantityCol.appendChild(quantityInput);

                                selectDiv.appendChild(selectCol);
                                selectDiv.appendChild(quantityCol);

                                document.getElementById('order_detail').appendChild(selectDiv);
                            }

                            function remove_sl_genre() {
                                var addGenreDiv = document.getElementById('order_detail');
                                var lastDiv = addGenreDiv.lastElementChild;
                                if (lastDiv) {
                                    addGenreDiv.removeChild(lastDiv);
                                }
                            }
                        </script>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id="error" class="error"></div>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type="button" class="btn btn-info input-left" name="btnSave" value="Update"
                            onClick="update_order();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function update_order() {
            $('#error').html("");
            var order_date = document.frmUpdateUser.order_date.value;
            var total_amount = document.frmUpdateUser.total_amount.value;
            var address = document.frmUpdateUser.address.value;
            var orderDetailDiv = document.getElementById("order_detail");
            var quantities = document.getElementsByName("quantities[]");
            if (order_date == "") {
                document.getElementById("error").innerText = "Order dates cannot be empty!!!";
                return;
            }
            if (total_amount < 0 || total_amount == "") {
                document.getElementById("error").innerText = "Total amount invalid!!!";
                return;
            }
            if (address == "") {
                document.getElementById("error").innerText = "Address cannot be empty!!!";
                return;
            }

            if (orderDetailDiv.childElementCount === 0) {
                document.getElementById("error").innerText = "Let's enter products!!!";
                return;
            }

            for (var i = 0; i < quantities.length; i++) {
                if (quantities[i].value < 1 || quantities[i].value === "") {
                    document.getElementById("error").innerText = "Let's enter quantities!!!";
                    return;
                }
            }

            document.frmUpdateUser.action = "update_order.php?id=<?php echo $id; ?>&task=update";
            document.frmUpdateUser.submit();
        }

        function goback() {
            location.href = "orders_management.php";
        }
    </script>

    <?php
}
?>