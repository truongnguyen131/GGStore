<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'save') {
        $fullname = $_POST['txtFullname'];
        $phone = $_POST['txtPhone'];
        $email = $_POST['txtEmailAddress'];
        $role = $_POST['slRole'];
        $username = $_POST['txtUsername'];
        $password = $_POST['txtPassword'];

        $check_query = "SELECT username FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);

        if ($check_stmt === false) {
            die("Error preparing statement");
        }

        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            createNotification("Username already exists! Add User Failed!","error",date('Y-m-d H:i:s'),"undisplayed"); 
            echo "<script>location.href='add_user.php';</script>";
        } else {
            $query = "INSERT INTO `users`(`username`, `password`, `role`, `full_name`, `phone_number`, `email`) VALUES (?,?,?,?,?,?)";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssssss", $username, $hashed_password, $role, $fullname, $phone,$email);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Add User Successful!","success",date('Y-m-d H:i:s'),"undisplayed");
            echo "<script>location.href='users_management.php';</script>";
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
                ADD A NEW USER
            </h4>
            <table>
                <tr>
                    <td>
                        Full Name
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtFullname" value="">
                    </td>
                    <td id="errorFullname" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Phone Number
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtPhone" value="">
                    </td>
                    <td id="errorPhone" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Email Address
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtEmailAddress" value="">
                    </td>
                    <td id="errorEmail" class="error"></td>
                </tr>
                <tr>
                    <td>
                        Role
                    </td>
                    <td>
                        <select class="form-control input-left" name="slRole">
                            <option value="user">User</option>
                            <option value="manufacturer">Manufacturer</option>
                        </select>
                    </td>
                    <td id="errorEmail" class="error"></td>
                </tr>
                <tr>
                    <td>
                        User Name
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="txtUsername" value="">
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
                        <input type="button" class="btn btn-info input-left" name="btnSave" value="Save" onClick="save();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function save() {
            const fullNameRegex = /^[\p{L} ]{4,}$/u;
            const phoneNumberRegex = /^0\d{9}$/;
            const emailRegex = /^[a-zA-Z0-9_.+-]+@gmail\.com$/;
            $('#errorPass').html("")
            $('#errorConfirmPass').html("")
            $('#errorFullname').html("")
            $('#errorPhone').html("")
            $('#errorEmail').html("")
            $('#errorUsername').html("")

            if (fullNameRegex.test(document.frmAddUser.txtFullname.value.trim()) != true) {
                $('#errorFullname').html("Fullname Invalid!!!")
                document.frmAddUser.txtFullname.value = document.frmAddUser.txtFullname.value.trim();
                document.frmAddUser.txtFullname.focus();
                return false;
            }
            if (phoneNumberRegex.test(document.frmAddUser.txtPhone.value.trim()) != true) {
                $('#errorPhone').html("The numberphone has 10 numbers and starts with 0")
                document.frmAddUser.txtPhone.value = document.frmAddUser.txtPhone.value.trim();
                document.frmAddUser.txtPhone.focus();
                return false;
            }
            if (emailRegex.test(document.frmAddUser.txtEmailAddress.value.trim()) != true) {
                $('#errorEmail').html("Email invalid")
                document.frmAddUser.txtEmailAddress.value = document.frmAddUser.txtEmailAddress.value.trim();
                document.frmAddUser.txtEmailAddress.focus();
                return false;
            }
            if (document.frmAddUser.txtUsername.value.trim().length < 5 || !isNaN(document.frmAddUser.txtUsername.value.trim())) {
                $('#errorUsername').html("Username has more than 4 characters and not is a number")
                document.frmAddUser.txtUsername.value = document.frmAddUser.txtUsername.value.trim();
                document.frmAddUser.txtUsername.focus();
                return false;
            }
            if (document.frmAddUser.txtPassword.value.trim() == "") {
                $('#errorPass').html("Password must not be empty")
                document.frmAddUser.txtPassword.value = document.frmAddUser.txtPassword.value.trim();
                document.frmAddUser.txtPassword.focus();
                return false;
            }
            if (document.frmAddUser.txtConfirmPassword.value.trim() != document.frmAddUser.txtPassword.value.trim()) {
                $('#errorConfirmPass').html("Confirm Password invalid")
                document.frmAddUser.txtConfirmPassword.value = document.frmAddUser.txtConfirmPassword.value.trim();
                document.frmAddUser.txtConfirmPassword.focus();
                return false;
            }
            else {
                document.frmAddUser.action = "add_user.php?task=save";
                document.frmAddUser.submit();
            }
        }

        function goback() {
            location.href = "users_management.php";
        }
    </script>

    <?php
}

?>