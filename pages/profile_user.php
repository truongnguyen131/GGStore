<?php
session_start();
include_once('./mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include "./mod/head.php"; ?>

<link rel="stylesheet" href="./assets/css/grand_custom.css">
<link rel="stylesheet" href="./assets/css/css_custom.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<body>
    <?php include "./mod/nav.php"; ?>

    <!-- START : Processing database -->
    <?php
    $username = isset($_SESSION["userName"]) ? $_SESSION["userName"] : "lvluyen902";
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
            $email_value =  $row['email'];
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
                <li><a href="index.html">Home</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><span>Profile User</span></li>
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
                                <ion-icon class="icon_changePass" id="changeInfor_btn" name="create-outline" title="Change Infor"></ion-icon>
                            </div>
                            <div class="nk-gap"></div>
                            <div class="row vertical-gap" id="change_infor">
                                <div class="col-sm-12">
                                    <label for="fullname">Full Name <span class="text-main-1">*</span>:</label>
                                    <input readonly type="text" class="form-control" name="fullname" id="fullname" value="<?= $fullname_value ?>">
                                    <span class="erro" id="errorFullname"></span>
                                </div>
                                <div class="col-sm-12">
                                    <label for="email">Email <span class="text-main-1">*</span>:</label>
                                    <input readonly type="email" class="form-control " name="email" id="email" value="<?= $email_value ?>">
                                    <span class="erro" id="errorEmail"></span>
                                </div>
                                <div class="col-sm-12">
                                    <label for="phone">Phone <span class="text-main-1">*</span>:</label>
                                    <input readonly type="number" class="form-control " name="phone" id="phone" value="<?= $phone_value ?>">
                                    <span class="erro" id="errorPhoneNumber"></span>
                                </div>
                                <div class="col-sm-12 animate__animated animate__bounceInLeft" id="btn_change_infor" style="display: none;">
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-main-1" type="button" onclick="check_infor()">Submit</button>
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-white" type="button" onclick="cancel_infor()">Cancel</buclass=>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="col-lg-12">
                        <form name="pass_user" method="post">
                            <div class="title_top">
                                <h4 class="titles">Change password</h4>
                                <ion-icon class="icon_changePass" id="changePass_btn" name="create-outline" title="Change Password"></ion-icon>
                            </div>
                            <div class="nk-gap"></div>
                            <div class="row vertical-gap changePassWord" id="changePass_form">
                                <div class="col-sm-12 currentPass">
                                    <label for="new_passs">New password <span class="text-main-1">*</span>:</label>
                                    <input readonly type="password" class="form-control " name="new_pass" id="new_pass">
                                    <span class="erro" id="errorNewPassword"></span>
                                </div>
                                <div class="col-sm-12">
                                    <label for="curent_pass">Current password <span class="text-main-1">*</span>:</label>
                                    <input readonly type="password" class="form-control" name="current_pass" id="current_pass">
                                    <span class="erro" id="errorCurentPassword"></span>
                                </div>
                                <div class="col-sm-12 animate__animated animate__bounceInLeft" id="btn_change_pass" style="display: none;">
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-main-1" type="button" onclick="check_password()">Change Password</button>
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-white" type="button" onclick="cancel_pass()">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Billing Details -->

            </div>
        </div>
        <div class="nk-gap-2"></div>



        <!-- START: Footer -->
        <footer class="nk-footer">

            <div class="container">
                <div class="nk-gap-3"></div>
                <div class="row vertical-gap">
                    <div class="col-md-6">
                        <div class="nk-widget">
                            <h4 class="nk-widget-title"><span class="text-main-1">Contact</span> With Us</h4>
                            <div class="nk-widget-content">
                                <form action="php/ajax-contact-form.php" class="nk-form nk-form-ajax">
                                    <div class="row vertical-gap sm-gap">
                                        <div class="col-md-6">
                                            <input type="email" class="form-control " name="email" placeholder="Email *">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control " name="name" placeholder="Name *">
                                        </div>
                                    </div>
                                    <div class="nk-gap"></div>
                                    <textarea class="form-control " name="message" rows="5" placeholder="Message *"></textarea>
                                    <div class="nk-gap-1"></div>
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-white">
                                        <span>Send</span>
                                        <span class="icon"><i class="ion-paper-airplane"></i></span>
                                    </button>
                                    <div class="nk-form-response-success"></div>
                                    <div class="nk-form-response-error"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="nk-widget">
                            <h4 class="nk-widget-title"><span class="text-main-1">Latest</span> Posts</h4>
                            <div class="nk-widget-content">
                                <div class="row vertical-gap sm-gap">
                                    <div class="col-lg-6">
                                        <div class="nk-widget-post-2">
                                            <a href="blog-article.html" class="nk-post-image">
                                                <img src="assets/images/post-1-sm.jpg" alt="">
                                            </a>
                                            <div class="nk-post-title"><a href="blog-article.html">Smell magic in the
                                                    air. Or maybe barbecue</a></div>
                                            <div class="nk-post-date">
                                                <span class="fa fa-calendar"></span> Sep 18, 2018
                                                <span class="fa fa-comments"></span> <a href="#">4</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="nk-widget-post-2">
                                            <a href="blog-article.html" class="nk-post-image">
                                                <img src="assets/images/post-2-sm.jpg" alt="">
                                            </a>
                                            <div class="nk-post-title"><a href="blog-article.html">Grab your sword and
                                                    fight the Horde</a></div>
                                            <div class="nk-post-date">
                                                <span class="fa fa-calendar"></span> Sep 5, 2018
                                                <span class="fa fa-comments"></span> <a href="#">7</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="nk-widget">
                            <h4 class="nk-widget-title"><span class="text-main-1">In</span> Twitter</h4>
                            <div class="nk-widget-content">
                                <div class="nk-twitter-list" data-twitter-count="1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-gap-3"></div>
            </div>

            <div class="nk-copyright">
                <div class="container">
                    <div class="nk-copyright-left">
                        <a target="_blank" href="https://www.templateshub.net">Templates Hub</a>
                    </div>
                    <div class="nk-copyright-right">
                        <ul class="nk-social-links-2">
                            <li><a class="nk-social-rss" href="#"><span class="fa fa-rss"></span></a></li>
                            <li><a class="nk-social-twitch" href="#"><span class="fab fa-twitch"></span></a></li>
                            <li><a class="nk-social-steam" href="#"><span class="fab fa-steam"></span></a></li>
                            <li><a class="nk-social-facebook" href="#"><span class="fab fa-facebook"></span></a></li>
                            <li><a class="nk-social-google-plus" href="#"><span class="fab fa-google-plus"></span></a>
                            </li>
                            <li><a class="nk-social-twitter" href="#" target="_blank"><span class="fab fa-twitter"></span></a></li>
                            <li><a class="nk-social-pinterest" href="#"><span class="fab fa-pinterest-p"></span></a>
                            </li>

                            <!-- Additional Social Buttons
                        <li><a class="nk-social-behance" href="#"><span class="fab fa-behance"></span></a></li>
                        <li><a class="nk-social-bitbucket" href="#"><span class="fab fa-bitbucket"></span></a></li>
                        <li><a class="nk-social-dropbox" href="#"><span class="fab fa-dropbox"></span></a></li>
                        <li><a class="nk-social-dribbble" href="#"><span class="fab fa-dribbble"></span></a></li>
                        <li><a class="nk-social-deviantart" href="#"><span class="fab fa-deviantart"></span></a></li>
                        <li><a class="nk-social-flickr" href="#"><span class="fab fa-flickr"></span></a></li>
                        <li><a class="nk-social-foursquare" href="#"><span class="fab fa-foursquare"></span></a></li>
                        <li><a class="nk-social-github" href="#"><span class="fab fa-github"></span></a></li>
                        <li><a class="nk-social-instagram" href="#"><span class="fab fa-instagram"></span></a></li>
                        <li><a class="nk-social-linkedin" href="#"><span class="fab fa-linkedin"></span></a></li>
                        <li><a class="nk-social-medium" href="#"><span class="fab fa-medium"></span></a></li>
                        <li><a class="nk-social-odnoklassniki" href="#"><span class="fab fa-odnoklassniki"></span></a></li>
                        <li><a class="nk-social-paypal" href="#"><span class="fab fa-paypal"></span></a></li>
                        <li><a class="nk-social-reddit" href="#"><span class="fab fa-reddit"></span></a></li>
                        <li><a class="nk-social-skype" href="#"><span class="fab fa-skype"></span></a></li>
                        <li><a class="nk-social-soundcloud" href="#"><span class="fab fa-soundcloud"></span></a></li>
                        <li><a class="nk-social-slack" href="#"><span class="fab fa-slack"></span></a></li>
                        <li><a class="nk-social-tumblr" href="#"><span class="fab fa-tumblr"></span></a></li>
                        <li><a class="nk-social-vimeo" href="#"><span class="fab fa-vimeo"></span></a></li>
                        <li><a class="nk-social-vk" href="#"><span class="fab fa-vk"></span></a></li>
                        <li><a class="nk-social-wordpress" href="#"><span class="fab fa-wordpress"></span></a></li>
                        <li><a class="nk-social-youtube" href="#"><span class="fab fa-youtube"></span></a></li>
                    -->
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END: Footer -->


    </div>

    <!-- START: Scripts -->
    <?php include "./mod/add_script.php"; ?>
    <!-- END: Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    }, function(data) {
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
                new_password : new_password
            }, function(data) {
                $('#notification_update').html(data);
            })
        }

    }
</script>

</html>