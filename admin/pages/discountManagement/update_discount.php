<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $sql_dID = "SELECT * FROM discounts WHERE id = $id";
    $result_dID = $conn->query($sql_dID);
    $row_dID = $result_dID->fetch_assoc();
    $product_id = $row_dID['product_id'];
    $percentage = $row_dID['discount_amount'];
    $start_date = $row_dID['start_date'];
    $end_date = $row_dID['end_date'];

    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {
        $product_id = $_POST['id_product'];
        $percentage = $_POST['percentage'];
        $start_date = $_POST['date_start'];
        $end_date = $_POST['date_end'];

        $sql_check_duplicate = "SELECT * FROM discounts WHERE product_id = $product_id 
            AND start_date <= '$end_date'
            AND end_date >= '$start_date' 
            AND id != $id";
        $result_check_duplicate = $conn->query($sql_check_duplicate);

        if ($result_check_duplicate->num_rows > 0) {
            createNotification("Duplicated updated data!! Update Discount Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_discount.php?id=$id';</script>";
        } else {
            $sql_update = "UPDATE discounts SET product_id = $product_id, discount_amount = $percentage, start_date = '$start_date', end_date = '$end_date' WHERE id = $id";
            if ($conn->query($sql_update) === TRUE) {
                createNotification("Update Discount Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='update_discount.php?id=$id';</script>";
            } else {
                createNotification("An error occurred during the update process!! Update Discount Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='update_discount.php?id=$id';</script>";
            }
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
        <form name="frmUpdateUser" method="post">
            <h4 class="ico_mug">
                UPDATE DISCOUNT
            </h4>
            <table>
                <tr>
                    <td>
                        Product Name
                    </td>
                    <td>
                        <select name="id_product" id="id_product" aria-controls="dataTable"
                            class="custom-select custom-select-sm form-control form-control-sm input-left">
                            <?php
                            $sql_products = "SELECT id,product_name FROM products WHERE release_date <= NOW()";
                            $result_products = $conn->query($sql_products);
                            while ($row_products = $result_products->fetch_assoc()) {
                                if ($row_products['id'] == $product_id) { ?>
                                    <option value="<?php echo $row_products["id"]; ?>" selected>
                                        <?php echo $row_products["product_name"]; ?>
                                    </option>
                                <?php } else { ?>
                                    <option value="<?php echo $row_products["id"]; ?>">
                                        <?php echo $row_products["product_name"]; ?>
                                    </option>
                                <?php }
                            }
                            ?>
                        </select>
                    </td>
                    <td id="errorProductname" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Percentage
                    </td>
                    <td>
                        <div class="input-group input-left">
                            <input type="number" value="<?php echo $percentage; ?>" name="percentage" name="percentage"
                                min="1" max="100" step="5" class="form-control">
                            <span class="input-group-text">%</span>
                        </div>
                    </td>
                    <td id="errorPercentage" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Date start
                    </td>
                    <td>
                        <input type="date" name="date_start" id="date_start" class="form-control form-control-sm input-left"
                            value="<?php echo $start_date; ?>" placeholder="" aria-controls="dataTable">
                    </td>
                    <td id="errorDateStart" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Date start
                    </td>
                    <td>
                        <input type="date" name="date_end" id="date_end" class="form-control form-control-sm input-left"
                            value="<?php echo $end_date; ?>" placeholder="" aria-controls="dataTable">
                    </td>
                    <td id="errorDateEnd" class="error"></td>
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
                            onClick="update_discount();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function update_discount() {
            $('#errorPercentage').html("");
            $('#errorDateStart').html("");
            $('#errorDateEnd').html("");
            $('#error').html("");

            var percentage = document.frmUpdateUser.percentage.value;
            var date_start = document.frmUpdateUser.date_start.value;
            var date_end = document.frmUpdateUser.date_end.value;
            var startDate = new Date(date_start);
            var endDate = new Date(date_end);

            if (isNaN(percentage) || percentage === "" || percentage < 1 || percentage > 100) {
                document.getElementById("errorPercentage").innerText = "Discount amount must be a number(1-100)";
                return;
            }
            if (date_start == "") {
                document.getElementById("errorDateStart").innerText = "The start-date cannot empty";
                return;
            }
            if (date_end == "") {
                document.getElementById("errorDateEnd").innerText = "The end-date cannot empty";
                return;
            }
            if (endDate < startDate) {
                document.getElementById("error").innerText = "The start-date can't be greater than the end-date";
                return;
            }
            else {
                document.frmUpdateUser.action = "update_discount.php?id=<?php echo $id; ?>&task=update";
                document.frmUpdateUser.submit();
            }
        }

        function goback() {
            location.href = "discounts_management.php";
        }
    </script>

    <?php
}
?>