<?php
include("../add_template.php");function main()
{
    include("../../../mod/database_connection.php");
    $id_account = $_SESSION["id_account"];
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {
        $old_pass = $_POST['txtOldPassword'];
        $new_pass = $_POST['txtNewPassword'];

        $sql = "SELECT `password` FROM `users` WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement");
        }

        $stmt->bind_param("i", $id_account);

        $stmt->execute();

        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($old_pass, $hashed_password)) {
            $query = "UPDATE `users` SET `password`= ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("si", $hashed_new_password, $id_account);
            $hashed_new_password = password_hash($new_pass, PASSWORD_DEFAULT);
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $stmt->close();
            $conn->close();
            createNotification("Update Password Successful!", "success", date('Y-m-d H:i:s'),"undisplayed");
            echo "<script>location.href='change_password.php';</script>";
        } else {
            createNotification("Old Password incorrect! Update Password Failed!", "error", date('Y-m-d H:i:s'),"undisplayed");
            echo "<script>location.href='change_password.php';</script>";
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
        <form name="frmChangePassword" method="post">
            <h4 class="ico_mug">
                UPDATE PASSWORD
            </h4>
            <table>
                <tr>
                    <td>
                        Old Password
                    </td>
                    <td>
                        <input type="password" class="form-control input-left" name="txtOldPassword">
                    </td>
                    <td id="errorOldPass" class="error"></td>
                </tr>
                <tr>
                    <td>
                        New Password
                    </td>
                    <td>
                        <input type="password" class="form-control input-left" name="txtNewPassword">
                    </td>
                    <td id="errorNewPass" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Confirm Password
                    </td>
                    <td>
                        <input type="password" class="form-control input-left" name="txtConfirmPassword">
                    </td>
                    <td id="errorConfirmPass" class="error"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="button" class="btn btn-info input-left" name="btnSave" value="Update"
                            onClick="update();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Clear" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>

        function update() {
            $('#errorOldPass').html("")
            $('#errorNewPass').html("")
            $('#errorConfirmPass').html("")

            if (document.frmChangePassword.txtOldPassword.value.trim() == "") {
                $('#errorOldPass').html("Old Password must not be empty")
                document.frmChangePassword.txtOldPassword.value = document.frmChangePassword.txtOldPassword.value.trim();
                document.frmChangePassword.txtOldPassword.focus();
                return false;
            }
            if (document.frmChangePassword.txtNewPassword.value.trim() == "") {
                $('#errorNewPass').html("New Password must not be empty")
                document.frmChangePassword.txtNewPassword.value = document.frmChangePassword.txtNewPassword.value.trim();
                document.frmChangePassword.txtNewPassword.focus();
                return false;
            }
            if (document.frmChangePassword.txtConfirmPassword.value.trim() != document.frmChangePassword.txtNewPassword.value.trim()) {
                $('#errorConfirmPass').html("Confirm Password invalid")
                document.frmChangePassword.txtConfirmPassword.value = document.frmChangePassword.txtConfirmPassword.value.trim();
                document.frmChangePassword.txtConfirmPassword.focus();
                return false;
            }
            else {
                document.frmChangePassword.action = "change_password.php?task=update";
                document.frmChangePassword.submit();
            }
        }

        function goback() {
            location.href = "change_password.php";
        }
    </script>

    <?php
}
?>