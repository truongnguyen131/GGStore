<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $id_account = $_SESSION["id_account"];
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {
        $fullname = $_POST['txtFullname'];
        $phone = $_POST['txtPhone'];
        $email = $_POST['txtEmailAddress'];
        $username = $_POST['txtUsername'];

        $check_query = "SELECT * FROM users WHERE username = ? AND full_name= ? AND phone_number = ? AND email = ?";
        $check_stmt = $conn->prepare($check_query);

        if ($check_stmt === false) {
            die("Error preparing statement");
        }

        $check_stmt->bind_param("ssss", $username, $fullname, $phone, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            createNotification("Username already exists! Update Profile Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='profile.php';</script>";
        } else {
            $query = "UPDATE users SET username = ?, full_name = ?, phone_number = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("ssssi", $username, $fullname, $phone, $email, $id_account);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Update Profile Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='profile.php';</script>";
        }

    } else {
        $query = "SELECT full_name, phone_number, email, username FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed");
        }
        $stmt->bind_param("i", $id_account);

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
        }

        $stmt->close();
        $conn->close();
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
        <form name="frmProfile" method="post">
            <h4 class="ico_mug">
                UPDATE PROFILE
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
                    <td></td>
                    <td>
                        <input type="button" class="btn btn-info input-left" name="btnSave" value="Update"
                            onClick="update_profile();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Clear" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function update_profile() {
            const fullNameRegex = /^[\p{L} ]{4,}$/u;
            const phoneNumberRegex = /^0\d{9}$/;
            const emailRegex = /^[a-zA-Z0-9_.+-]+@gmail\.com$/;
            $('#errorFullname').html("")
            $('#errorPhone').html("")
            $('#errorEmail').html("")
            $('#errorUsername').html("")

            if (fullNameRegex.test(document.frmProfile.txtFullname.value.trim()) != true) {
                $('#errorFullname').html("Fullname has more than 3 characters")
                document.frmProfile.txtFullname.value = document.frmProfile.txtFullname.value.trim();
                document.frmProfile.txtFullname.focus();
                return false;
            }
            if (phoneNumberRegex.test(document.frmProfile.txtPhone.value.trim()) != true) {
                $('#errorPhone').html("The numberphone has 10 numbers and starts with 0")
                document.frmProfile.txtPhone.value = document.frmProfile.txtPhone.value.trim();
                document.frmProfile.txtPhone.focus();
                return false;
            }
            if (emailRegex.test(document.frmProfile.txtEmailAddress.value.trim()) != true) {
                $('#errorEmail').html("Email invalid")
                document.frmProfile.txtEmailAddress.value = document.frmProfile.txtEmailAddress.value.trim();
                document.frmProfile.txtEmailAddress.focus();
                return false;
            }
            if (document.frmProfile.txtUsername.value.trim().length < 5 || !isNaN(document.frmProfile.txtUsername.value.trim())) {
                $('#errorUsername').html("Username has more than 4 characters and not is a number")
                document.frmProfile.txtUsername.value = document.frmProfile.txtUsername.value.trim();
                document.frmProfile.txtUsername.focus();
                return false;
            }
            else {
                document.frmProfile.action = "profile.php?task=update";
                document.frmProfile.submit();
            }
        }

        function goback() {
            location.href = "profile.php";
        }
    </script>

    <?php
}
?>