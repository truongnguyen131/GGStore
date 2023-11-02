<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    if (isset($_GET["task"]) && $_GET["task"] == "save") {
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
            createNotification("Duplicate data!! Add Voucher Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_voucher.php';</script>";
        } else {
            $sql_insert = 'INSERT INTO `vouchers`(`value`, `type`) VALUES (?,?)';
            $sql_insert_stmt = $conn->prepare($sql_insert);
            $sql_insert_stmt->bind_param("is", $voucher_value, $voucher_type);

            if ($sql_insert_stmt->execute()) {

                $id_voucher = mysqli_insert_id($conn);

                if ($minimum_condition > 0) {
                    $sql = "UPDATE `vouchers` SET `minimum_condition`='$minimum_condition'WHERE id = $id_voucher";
                    $conn->query($sql);
                }
                if ($quantity > 0) {
                    $sql = "UPDATE `vouchers` SET `quantity`='$quantity'WHERE id = $id_voucher";
                    $conn->query($sql);
                }

                if ($date_expiry != "") {
                    $sql = "UPDATE `vouchers` SET `date_expiry`='$date_expiry'WHERE id = $id_voucher";
                    $conn->query($sql);
                }

                createNotification("Add Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='add_voucher.php';</script>";
            } else {
                createNotification("An error occurred during the add process!! Add Voucher Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='add_voucher.php';</script>";
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
        <form name="frmAddUser" method="post">
            <h4 class="ico_mug">
                ADD A NEW VOUCHER
            </h4>
            <table>
                <tr>
                    <td>
                        Voucher value
                    </td>
                    <td>
                        <input type="number" min="1" value="0" name="voucher_value"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Voucher type
                    </td>
                    <td>
                        <select class="form-control input-left" name="voucher_type">
                            <option value="percent">Percent</option>
                            <option value="gcoin">Gcoin</option>
                            <option value="freeship">Freeship</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Minimum condition
                    </td>
                    <td>
                        <input type="number" name="minimum_condition" min="0" value="0"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Quantity
                    </td>
                    <td>
                        <input type="number" name="quantity" class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Date expiry
                    </td>
                    <td>
                        <input type="date" name="date_expiry" min="<?= date("Y-m-d") ?>"
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
                            onClick="save();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function save() {
            document.getElementById("error").innerText = "";
            var voucher_value = document.frmAddUser.voucher_value.value;
            var minimum_condition = document.frmAddUser.minimum_condition.value;
            var quantity = document.frmAddUser.quantity.value;

            if (voucher_value <= 0 || voucher_value === "") {
                document.getElementById("error").innerText = "The voucher value must be greater than 0!!!";
                return;
            }

            if (minimum_condition < 0 || minimum_condition === "") {
                document.getElementById("error").innerText = "The minimum condition value must be a positive number!!!";
                return;
            }

            if (quantity < 0) {
                document.getElementById("error").innerText = "The quantity value must be a positive number!!!";
                return;
            }

            document.frmAddUser.action = "add_voucher.php?task=save";
            document.frmAddUser.submit();

        }

        function goback() {
            location.href = "vouchers_management.php";
        }
    </script>

    <?php
}
?>