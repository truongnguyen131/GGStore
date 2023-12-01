<?php
session_start();
include_once('../mod/database_connection.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="./assets/css/log_ris_for.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- IonIcons -->
    <link rel="stylesheet" href="./assets/vendor/ionicons/css/ionicons.min.css">

    <!-- GoodGames -->
    <link rel="stylesheet" href="./assets/css/goodgames1.css">

</head>

<style>
    body {
        position: relative;
        width: 100%;
        height: 100vh;
        background-image: url(./assets/images/slider_bg02.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        overflow: hidden;
    }

    body::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        translate: -50% -50%;
        background-image: url(./assets/images/slider_circle.png);
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        width: 600px;
        height: 600px;
        opacity: 0.1;
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }


    #modalSearch {
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.7);
        position: relative;
    }

    .modal_box {
        position: absolute;
        z-index: 101;
        top: 50%;
        left: 50%;
        translate: -50% -50%;
        margin: 0 !important;
    }

    .from_title h3 {
        font-size: 25px;
    }
</style>


<body>

    <?php
    if (isset($_POST['txtFullname'])) {
        $fullname = $_POST['txtFullname'];
        $phone = $_POST['txtTelephone'];
        $email = $_POST['txtEmail'];
        $username = $_POST['txtUsername'];

        $query = "SELECT * FROM users WHERE full_name = ? AND phone_number = ? AND email = ? AND username = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Error preparing statement");
        }
        $stmt->bind_param("ssss", $fullname, $phone, $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $numbers = '0123456789';
            $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $pass_new = '';
            $n = rand(2, 4);
            $l = rand(6, 8);
            for ($i = 0; $i < $n; $i++)
                $pass_new .= $numbers[rand(0, 9)];
            for ($i = 0; $i < $l; $i++)
                $pass_new .= $letters[rand(0, 25)];

            $pass_new_arr = str_split($pass_new);
            shuffle($pass_new_arr);
            $pass_new = implode('', $pass_new_arr);
            $hass_pass = password_hash($pass_new, PASSWORD_DEFAULT);

            $update_pass = "UPDATE `users` SET `password`= ? WHERE username = ?";
            $stmts = $conn->prepare($update_pass);
            $stmts->bind_param("ss", $hass_pass, $username);
            if ($stmts->execute()) {
                echo " <script>
            $.post('./pages/send_mail.php', {
                fullname: '" . $fullname . "',
                email: '" . $email . "',
                password: '" . $pass_new . "'
            }, function(data) {
                $('#send_mail').html(data);
            }); </script> ";
            }
    
        } else {
            echo "<p>Incorrect user information</p>";
        }
    }
    ?>
    <a id="dialog_notification" data-toggle="modal" style="display: none;" data-target="#modalSearch">a
    </a>
    <!-- START: Dialog Notification -->
    <div class="nk-modal modal fade" id="modalSearch" style="display: none;" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal_box animate__animated animate__rubberBand" role="document">
            <div class="modal-content">
                <div class="modal-body" style="padding: 20px;">
                    <div id="div_status" style="margin-bottom: 0;" class="nk-info-box ">
                        <div class="nk-info-box-icon">
                            <i id="i_status" class=""></i>
                        </div>
                        <h3 id="status"></h3>
                        <em id="message"></em>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Dialog Notification -->
    <script>
        function notification_dialog(status, message) {
            const modalSearch = document.getElementById('modalSearch');
            modalSearch.style.display = 'block';
            document.getElementById("status").innerHTML = status;
            document.getElementById("message").innerHTML = message;

            document.getElementById("div_status").classList.add("text-success");
            document.getElementById("i_status").classList.add("ion-checkmark-round");
            if (status != "Success") {
                document.getElementById("div_status").classList.add("text-danger");
                document.getElementById("i_status").classList.add("ion-close-round");
            }

            document.getElementById("dialog_notification").click();
            // Thêm sự kiện lắng nghe click cho cả trang web
            document.addEventListener("click", function (event) {
                // Kiểm tra xem phần tử được nhấp có là modalSearch hay không
                if (!modalSearch.contains(event.target)) {
                    // Nếu không phải, ẩn modalSearch đi
                    modalSearch.style.display = 'none';
                }
            });
        }
    </script>

    <p id="send_mail"></p>
    <form name="frmForgotPassword" action="./forgot_password.php" method="post" class="form_login">
        <div class="from_title">
            <h3>Confirm information</h3>
        </div>

        <div class="form_item">
            <label for="txtFullname">Full name</label>
            <div class="form_item__input" id="divFullName">
                <input type="text" name="txtFullname" id="txtFullname">
            </div>
            <label class="erro" id="errorFullname"></label>
        </div>

        <div class="form_item">
            <label for="txtTelephone">Telephone</label>
            <div class="form_item__input" id="divPhone">
                <input type="tel" name="txtTelephone" id="txtTelephone">
            </div>
            <label class="erro" id="errorTelephone"></label>
        </div>
        <div class="form_item">
            <label for="txtEmail">Email</label>
            <div class="form_item__input" id="divEmail">
                <input type="email" name="txtEmail" id="txtEmail">
            </div>
            <label class="erro" id="errorEmail"></label>
        </div>

        <div class="form_item">
            <label for="txtUsername">Username</label>
            <div class="form_item__input" id="divUserName">
                <input type="text" name="txtUsername" id="txtUsername">
            </div>
            <label class="erro" id="errorUsername"></label>
        </div>

        <div class="form_item form__btn">
            <button onclick="confirm_info()" type="button">Confirm</button>
            <button type="button" onclick="back()">Back</button>
        </div>
    </form>
</body>

<script>
    function confirm_info() {
        document.getElementById("errorFullname").innerHTML = "";
        document.getElementById("errorTelephone").innerHTML = "";
        document.getElementById("errorEmail").innerHTML = "";
        document.getElementById("errorUsername").innerHTML = "";

        const fullNameRegex = /^[\p{L} ]{4,}$/u;
        const phoneNumberRegex = /^0\d{9}$/;
        const emailRegex = /^[a-zA-Z0-9_.+-]+@gmail\.com$/;

        var fullname = document.getElementById("txtFullname").value;
        var phone = document.getElementById("txtTelephone").value;
        var email = document.getElementById("txtEmail").value;
        var username = document.getElementById("txtUsername").value;

        if (fullNameRegex.test(fullname) != true) {
            $('#divFullName').addClass('is-invalid');
            $('#errorFullname').html("Fullname has more than 3 characters")
            document.getElementById("txtFullname").focus()
            return;
        } else {
            $('#divFullName').removeClass('is-invalid')
            $('#errorFullname').html("")
        }

        if (phoneNumberRegex.test(phone) != true) {
            $('#divPhone').addClass('is-invalid');
            $('#errorTelephone').html("The numberphone has 10 numbers and starts with 0")
            document.getElementById("txtTelephone").focus()
            return;
        } else {
            $('#divPhone').removeClass('is-invalid')
            $('#errorPhoneNumber').html("")
        }

        if (emailRegex.test(email) != true) {
            $('#divEmail').addClass('is-invalid');
            $('#errorEmail').html("Email invalid")
            document.getElementById("txtEmail").focus()
            return;
        } else {
            $('#divEmail').removeClass('is-invalid')
            $('#errorEmail').html("")
        }

        if (username.length < 5 || !isNaN(username)) {
            $('#divUserName').addClass('is-invalid');
            $('#errorUsername').html("Username has more than 4 characters and not is a number")
            document.getElementById("txtUsername").focus()
            return;
        } else {
            document.frmForgotPassword.submit();
        }

    }

    function back() {
        window.location = "./pages/login.php";
    }
</script>

</html>