<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    $query = "SELECT full_name, phone_number, email, username,password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed");
    }
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullname = $row['full_name'];
        $phone = $row['phone_number'];
        $email = $row['email'];
        $username = $row['username'];
        $old_password = $row['password'];
    }

    $stmt->close();

    if ($task == 'update') {
        $fullname = $_POST['txtFullname'];
        $phone = $_POST['txtPhone'];
        $email = $_POST['txtEmailAddress'];
        $username = $_POST['txtUsername'];
        $password = $_POST['txtPassword'];

        $check_query = "SELECT * FROM users WHERE username = ? AND id != ?";
        $check_stmt = $conn->prepare($check_query);

        if ($check_stmt === false) {
            die("Error preparing statement");
        }

        $check_stmt->bind_param("si", $username, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            createNotification("Username already exists! Update User Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_user.php?id=$id';</script>";
        } else {
            $query = "UPDATE users SET username = ?,password = ?, full_name = ?, phone_number = ?, email = ? WHERE id = ?";
            $stmt_update = $conn->prepare($query);

            if (!$stmt_update) {
                die("Prepare failed");
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if($password == ""){
                $hashed_password = $old_password;
            }
            $stmt_update->bind_param("sssssi", $username, $hashed_password, $fullname, $phone, $email, $id);

            if (!$stmt_update->execute()) {
                die("Execute failed: " . $stmt_update->error);
            }

            $check_stmt->close();
            $stmt_update->close();
            $conn->close();
            createNotification("Update User Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_user.php?id=$id';</script>";
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
                UPDATE USER
            </h4>
            <table>
                <tr>
                    <td>
                        Full Name
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtFullname"
                            value="<?php echo $fullname ?>">
                    </td>
                    <td id="errorFullname" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Phone Number
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtPhone" value="<?php echo $phone ?>">
                    </td>
                    <td id="errorPhone" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Email Address
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtEmailAddress"
                            value="<?php echo $email ?>">
                    </td>
                    <td id="errorEmail" class="error"></td>
                </tr>
                <tr>
                    <td>
                        User Name
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtUsername"
                            value="<?php echo $username ?>">
                    </td>
                    <td id="errorUsername" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Password
                    </td>
                    <td>
                        <input type="password" class="form-control input-left" name="txtPassword">
                    </td>
                    <td id="errorPass" class="error"></td>
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
                            onClick="update_user();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function update_user() {
            const fullNameRegex = /^[\p{L} ]{4,}$/u;
            const phoneNumberRegex = /^0\d{9}$/;
            const emailRegex = /^[a-zA-Z0-9_.+-]+@gmail\.com$/;
            $('#errorFullname').html("")
            $('#errorPhone').html("")
            $('#errorEmail').html("")
            $('#errorUsername').html("")
            $('#errorPass').html("")
            $('#errorConfirmPass').html("")

            if (fullNameRegex.test(document.frmUpdateUser.txtFullname.value.trim()) != true) {
                $('#errorFullname').html("Fullname Invalid!!!")
                document.frmUpdateUser.txtFullname.value = document.frmUpdateUser.txtFullname.value.trim();
                document.frmUpdateUser.txtFullname.focus();
                return false;
            }
            if (phoneNumberRegex.test(document.frmUpdateUser.txtPhone.value.trim()) != true) {
                $('#errorPhone').html("The numberphone has 10 numbers and starts with 0")
                document.frmUpdateUser.txtPhone.value = document.frmUpdateUser.txtPhone.value.trim();
                document.frmUpdateUser.txtPhone.focus();
                return false;
            }
            if (emailRegex.test(document.frmUpdateUser.txtEmailAddress.value.trim()) != true) {
                $('#errorEmail').html("Email invalid")
                document.frmUpdateUser.txtEmailAddress.value = document.frmUpdateUser.txtEmailAddress.value.trim();
                document.frmUpdateUser.txtEmailAddress.focus();
                return false;
            }
            if (document.frmUpdateUser.txtUsername.value.trim().length < 5 || !isNaN(document.frmUpdateUser.txtUsername.value.trim())) {
                $('#errorUsername').html("Username has more than 4 characters and not is a number")
                document.frmUpdateUser.txtUsername.value = document.frmUpdateUser.txtUsername.value.trim();
                document.frmUpdateUser.txtUsername.focus();
                return false;
            }

            if (document.frmUpdateUser.txtConfirmPassword.value.trim() != document.frmUpdateUser.txtPassword.value.trim()) {
                $('#errorConfirmPass').html("Confirm Password invalid")
                document.frmUpdateUser.txtConfirmPassword.value = document.frmUpdateUser.txtConfirmPassword.value.trim();
                document.frmUpdateUser.txtConfirmPassword.focus();
                return false;
            }
            else {
                document.frmUpdateUser.action = "update_user.php?id=<?php echo $id; ?>&task=update";
                document.frmUpdateUser.submit();
            }
        }

        function goback() {
            location.href = "users_management.php";
        }
    </script>

    <?php
}
?>