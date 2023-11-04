<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $sql_dID = "SELECT * FROM vouchers WHERE id = $id";
    $result_dID = $conn->query($sql_dID);
    $row_dID = $result_dID->fetch_assoc();
    $voucher_value = $row_dID['value'];
    $voucher_type = $row_dID['type'];
    $minimum_condition = $row_dID['minimum_condition'];
    $quantity = $row_dID['quantity'];
    $date_expiry = $row_dID['date_expiry'];


    if (isset($_GET["task"]) && $_GET["task"] == 'update') {
        $voucher_value = $_POST['voucher_value'];
        $voucher_type = $_POST['voucher_type'];
        $minimum_condition = $_POST['minimum_condition'];
        $quantity = $_POST['quantity'];
        $date_expiry = $_POST['date_expiry'];

        $check_duplicate_sql = "SELECT * FROM vouchers WHERE value = $voucher_value AND type = '$voucher_type' AND minimum_condition = $minimum_condition";

        if ($quantity == "") {
            $check_duplicate_sql .= " AND quantity is null";
        } else {
            $check_duplicate_sql .= " AND quantity = $quantity";
        }

        if ($date_expiry == "") {
            $check_duplicate_sql .= " AND date_expiry is null";
        } else {
            $check_duplicate_sql .= " AND date_expiry = $date_expiry";
        }

        $check_duplicate_stmt = $conn->prepare($check_duplicate_sql);
        $check_duplicate_stmt->execute();
        $check_duplicate_result = $check_duplicate_stmt->get_result();

        if ($check_duplicate_result->num_rows > 0) {
            createNotification("Duplicate data!! Update Voucher Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_voucher.php?id=$id';</script>";
        } else {
            $sql = "UPDATE `vouchers` SET `value`='$voucher_value',`type`='$voucher_type',`minimum_condition`='$minimum_condition' WHERE id = $id";
            $conn->query($sql);
            if ($quantity != "") {
                $sql = "UPDATE `vouchers` SET `quantity`='$quantity' WHERE id = $id";
                $conn->query($sql);
            } else {
                $sql = "UPDATE `vouchers` SET `quantity`= NULL WHERE id = $id";
                $conn->query($sql);
            }

            if ($date_expiry != "") {
                $sql = "UPDATE `vouchers` SET `date_expiry`='$date_expiry' WHERE id = $id";
                $conn->query($sql);
            } else {
                $sql = "UPDATE `vouchers` SET `date_expiry`= NULL WHERE id = $id";
                $conn->query($sql);
            }

            createNotification("Update Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_voucher.php?id=$id';</script>";
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
                UPDATE VOUCHER
            </h4>
            <table>
                <tr>
                    <td>
                        Voucher Value
                    </td>
                    <td>
                        <input type="number" min="1" value="<?= $voucher_value ?>" name="voucher_value"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Voucher Type
                    </td>
                    <td>
                        <select class="form-control input-left" name="voucher_type">
                            <option value="percent" <?= ($voucher_type == "percent") ? "selected" : "" ?>>Percent</option>
                            <option value="gcoin" <?= ($voucher_type == "gcoin") ? "selected" : "" ?>>Gcoin</option>
                            <option value="freeship" <?= ($voucher_type == "freeship") ? "selected" : "" ?>>Freeship</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Minimum condition
                    </td>
                    <td>
                        <input type="number" name="minimum_condition" min="0" value="<?= $minimum_condition ?>"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Quantity
                    </td>
                    <td>
                        <input type="number" name="quantity" min="1" value="<?= $quantity ?>"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Date Expiry
                    </td>
                    <td>
                        <input type="date" name="date_expiry" value="<?= $date_expiry ?>"
                            class="form-control form-control-sm input-left">
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
                            onClick="update_voucher();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function update_voucher() {
            document.getElementById("error").innerText = "";
            var voucher_value = document.frmUpdateUser.voucher_value.value;
            var minimum_condition = document.frmUpdateUser.minimum_condition.value;
            var quantity = document.frmUpdateUser.quantity.value;

            if (voucher_value <= 0 || voucher_value === "") {
                document.getElementById("error").innerText = "The voucher value must be greater than 0!!!";
                return;
            }

            if (minimum_condition < 0 || minimum_condition === "") {
                document.getElementById("error").innerText = "The minimum condition value must be a positive number!!!";
                return;
            }

            if (quantity < 0) {
                document.getElementById("error").innerText = "The quantity value must be a positive number!!";
                return;
            } else {
                document.frmUpdateUser.action = "update_voucher.php?id=<?php echo $id; ?>&task=update";
                document.frmUpdateUser.submit();
            }
        }

        function goback() {
            location.href = "vouchers_management.php";
        }
    </script>

    <?php
}
?>