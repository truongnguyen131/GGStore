<?php
session_start();
include_once('../mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../mod/head.php"; ?>

<style>
    /* Profile */
    .title_top {
        display: flex;
        column-gap: 10px;
    }

    .titles {
        font-size: 20px;
        margin-bottom: 0;
        color: whitesmoke;
        text-shadow: 2px 2px 5px #dd163b;
        text-decoration: underline;
    }

    .title_top .icon_changePass {
        cursor: pointer;
        width: 25px;
        height: 25px;
        color: white;
        transition: .3s linear;
    }

    .title_top .icon_changePass:hover {
        color: #dd163b;
    }

    .title_top .icon_changePass.active {
        color: #dd163b;
    }

    #changePass_form,
    #change_infor {
        margin-top: 5px;
    }

    #fullname[readonly],
    #email[readonly],
    #phone[readonly],
    #new_pass[readonly],
    #current_pass[readonly] {
        background-color: transparent;
    }
</style>

<body>
    <?php include "../mod/nav.php"; ?>

    <!-- START : Processing database -->
    <?php
    $username = isset($_SESSION["userName"]) ? $_SESSION["userName"] : "";
    $sql_user = "SELECT full_name,phone_number,email FROM `users` WHERE username = ?";
    $stm = $conn->prepare($sql_user);
    $stm->bind_param("s", $username);
    if (!$stm->execute()) {
        die("Execute failed: " . $stm->error);
    }
    $result = $stm->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fullname_value = $row['full_name'];
            $phone_value = $row['phone_number'];
            $email_value = $row['email'];
        }
    }


    ?>
    <!-- END : Processing database -->
    <div id="notification_update"></div>
    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">
                <li><a href="../Galaxy_Game_Store/home">Home</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><a href="../Galaxy_Game_Store/profile">Profile</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><span>User's Profile</span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">
            <div class="nk-store nk-store-checkout">
                <!-- START: Billing Details -->
                <div class="nk-gap"></div>
                <div class="row vertical-gap">

                    <div class="col-lg-12">
                        <form name="infor_user" method="post">
                            <div class="title_top">
                                <h4 class="titles">User Infor</h4>
                                <ion-icon class="icon_changePass" id="changeInfor_btn" name="create-outline"
                                    title="Change Infor"></ion-icon>
                            </div>
                            <div class="nk-gap"></div>
                            <div class="row vertical-gap" id="change_infor">
                                <div class="col-sm-12">
                                    <label for="fullname">Full Name <span class="text-main-1">*</span>:</label>
                                    <input readonly type="text" class="form-control" name="fullname" id="fullname"
                                        value="<?= $fullname_value ?>">
                                    <span class="erro" id="errorFullname"></span>
                                </div>
                                <div class="col-sm-12">
                                    <label for="email">Email <span class="text-main-1">*</span>:</label>
                                    <input readonly type="email" class="form-control " name="email" id="email"
                                        value="<?= $email_value ?>">
                                    <span class="erro" id="errorEmail"></span>
                                </div>
                                <div class="col-sm-12">
                                    <label for="phone">Phone <span class="text-main-1">*</span>:</label>
                                    <input readonly type="number" class="form-control " name="phone" id="phone"
                                        value="<?= $phone_value ?>">
                                    <span class="erro" id="errorPhoneNumber"></span>
                                </div>
                                <div class="col-sm-12 animate__animated animate__bounceInLeft" id="btn_change_infor"
                                    style="display: none;">
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-main-1" type="button"
                                        onclick="check_infor()">Submit</button>
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-white" type="button"
                                        onclick="cancel_infor()">Cancel</buclass=>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="col-lg-12">
                        <form name="pass_user" method="post">
                            <div class="title_top">
                                <h4 class="titles">Change password</h4>
                                <ion-icon class="icon_changePass" id="changePass_btn" name="create-outline"
                                    title="Change Password"></ion-icon>
                            </div>
                            <div class="nk-gap"></div>
                            <div class="row vertical-gap changePassWord" id="changePass_form">
                                <div class="col-sm-12 currentPass">
                                    <label for="new_passs">New password <span class="text-main-1">*</span>:</label>
                                    <input readonly type="password" class="form-control " name="new_pass" id="new_pass">
                                    <span class="erro" id="errorNewPassword"></span>
                                </div>
                                <div class="col-sm-12">
                                    <label for="curent_pass">Confirm password <span
                                            class="text-main-1">*</span>:</label>
                                    <input readonly type="password" class="form-control" name="current_pass"
                                        id="current_pass">
                                    <span class="erro" id="errorCurentPassword"></span>
                                </div>
                                <div class="col-sm-12 animate__animated animate__bounceInLeft" id="btn_change_pass"
                                    style="display: none;">
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-main-1" type="button"
                                        onclick="check_password()">Change Password</button>
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-white" type="button"
                                        onclick="cancel_pass()">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Billing Details -->

            </div>
        </div>
        <div class="nk-gap-2"></div>



    </div>

    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->

    <!-- <p id="getProcessProfileUser"></p> -->
</body>
<script>
    const changeInfor_btn = document.getElementById('changeInfor_btn');
    const changePass_btn = document.getElementById('changePass_btn');
    const btn_change_infor = document.getElementById('btn_change_infor');
    const btn_change_pass = document.getElementById('btn_change_pass');
    var fullname_user = "<?= $fullname_value ?>";
    var phone_user = "<?= $phone_value ?>";
    var email_user = "<?= $email_value ?>";
    var user_name = "<?= $username ?>";

    changeInfor_btn.onclick = () => {
        $('#fullname').focus();
        $('#fullname').removeAttr('readonly');
        $('#phone').removeAttr('readonly');
        $('#email').removeAttr('readonly');
        changeInfor_btn.classList.add("active");
        btn_change_infor.style.display = "block";
    }
    changePass_btn.onclick = () => {

        $('#new_pass').removeAttr('readonly');
        $('#current_pass').removeAttr('readonly');
        changePass_btn.classList.add("active");
        btn_change_pass.style.display = "block";
        $('#new_pass').focus();
    }

    function cancel_infor() {
        $('#fullname').val(fullname_user)
        $('#phone').val(phone_user)
        $('#email').val(email_user)
        $('#fullname').removeClass('is-invalid')
        $('#phone').removeClass('is-invalid')
        $('#email').removeClass('is-invalid')
        $('#errorFullname').html("")
        $('#errorPhoneNumber').html("")
        $('#errorEmail').html("")
        $('#fullname').attr('readonly', 'readonly');
        $('#phone').attr('readonly', 'readonly');
        $('#email').attr('readonly', 'readonly');
        changeInfor_btn.classList.remove("active");
        btn_change_infor.style.display = "none";
    }

    function cancel_pass() {
        $('#new_pass').attr('readonly', 'readonly');
        $('#current_pass').attr('readonly', 'readonly');
        $('#new_pass').val("")
        $('#current_pass').val("")
        $('#new_pass').removeClass('is-invalid')
        $('#current_pass').removeClass('is-invalid')
        $('#errorNewPassword').html("")
        $('#errorCurentPassword').html("")
        changePass_btn.classList.remove("active");
        btn_change_pass.style.display = "none";
    }

    function check_infor() {
        var check = 0
        var fullname = $('#fullname').val()
        var phone = $('#phone').val()
        var email = $('#email').val()

        const fullNameRegex = /^[\p{L} ]{1,} [\p{L} ]{1,}$/u;
        const phoneNumberRegex = /^0\d{9}$/;
        const emailRegex = /^[a-zA-Z0-9_.+-]+@gmail\.com$/;


        if ($('#fullname').prop("readonly") && $('#phone').prop("readonly") && $('#email').prop("readonly")) {
            $('#fullname').removeClass('is-invalid')
            $('#errorFullname').html("")
            $('#phone').removeClass('is-invalid')
            $('#errorPhoneNumber').html("")
            $('#email').removeClass('is-invalid')
            $('#errorEmail').html("")
        } else {
            if (fullNameRegex.test(fullname) != true) {
                $('#fullname').addClass('is-invalid');
                $('#fullname').focus();
                $('#errorFullname').html("The full name is not in the correct format. VD: Nguyễn Văn A")
            } else {
                $('#fullname').removeClass('is-invalid')
                $('#errorFullname').html("")
                check += 1
            }

            if (phoneNumberRegex.test(phone) != true) {
                $('#phone').addClass('is-invalid');
                $('#phone').focus();
                $('#errorPhoneNumber').html("The numberphone has 10 numbers and starts with 0")
            } else {
                $('#phone').removeClass('is-invalid')
                $('#errorPhoneNumber').html("")
                check += 1
            }

            if (emailRegex.test(email) != true) {
                $('#email').addClass('is-invalid');
                $('#errorEmail').html("Email invalid")
            } else {
                $('#email').removeClass('is-invalid')
                $('#errorEmail').html("")
                check += 1

                if (check == 3) {
                    $.post('./pages/update_profile_user.php?task=change_infor', {
                        fullname: fullname,
                        phone: phone,
                        email: email,
                        username: user_name
                    }, function (data) {
                        $('#notification_update').html(data);
                    })

                }
            }
        }

    }

    function check_password() {
        var check = 0
        var new_password = $('#new_pass').val()
        var current_password = $('#current_pass').val()

        if (new_password.length == 0) {
            $('#new_pass').addClass('is-invalid');
            $('#new_pass').focus();
            $('#errorNewPassword').html("Password must not be empty")
        } else {
            $('#new_pass').removeClass('is-invalid')
            $('#errorNewPassword').html("")
            check += 1
        }

        if (current_password != new_password) {
            $('#current_pass').addClass('is-invalid');
            $('#current_pass').focus();
            $('#errorCurentPassword').html("Password confirmation must be the same as New Password")
        } else {
            $('#current_pass').removeClass('is-invalid')
            $('#errorCurentPassword').html("")
            check += 1
        }

        if (check == 2) {
            $.post('./pages/update_profile_user.php?task=change_pass', {
                new_password: new_password,
                username: user_name
            }, function (data) {
                $('#notification_update').html(data);
            })
        }

    }
</script>

</html>