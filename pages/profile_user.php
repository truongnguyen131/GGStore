<?php
session_start();
include_once('../mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../mod/head.php"; ?>

<body>

    <?php include "../mod/nav.php"; ?>


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
                <form action="#" class="nk-form">
                    <div class="row vertical-gap">
                        <div class="col-lg-12">
                            <h4 class="titles">User info</h4>
                            <div class="nk-gap"></div>
                            <div class="row vertical-gap">
                                <div class="col-sm-12">
                                    <label for="fname">Full Name <span class="text-main-1">*</span>:</label>
                                    <input type="text" class="form-control required" name="fname" id="fname">
                                </div>
                                <div class="col-sm-12">
                                    <label for="fname">Email <span class="text-main-1">*</span>:</label>
                                    <input type="email" class="form-control required" name="fname" id="fname">
                                </div>
                                <div class="col-sm-12">
                                    <label for="fname">Phone <span class="text-main-1">*</span>:</label>
                                    <input type="number" class="form-control required" name="fname" id="fname">
                                </div>
                                <div class="col-sm-12">
                                    <label for="fname">Address <span class="text-main-1">*</span>:</label>
                                    <input type="number" class="form-control required" name="fname" id="fname">
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h4 class="titles">Change password</h4>
                            <div class="nk-gap"></div>
                            <div class="row vertical-gap">
                                <div class="col-sm-12">
                                    <label for="fname">Current password <span class="text-main-1">*</span>:</label>
                                    <input type="password" class="form-control required" name="fname" id="fname">
                                </div>
                                <div class="col-sm-12">
                                    <label for="fname">New password <span class="text-main-1">*</span>:</label>
                                    <input type="password" class="form-control required" name="fname" id="fname">
                                </div>

                                <div class="col-sm-12">
                                    <a class="nk-btn nk-btn-rounded nk-btn-color-main-1" href="#">Submit</a>
                                    <a class="nk-btn nk-btn-rounded nk-btn-color-white" href="#">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END: Billing Details -->

            </div>
        </div>

        <div class="nk-gap-3"></div>

        <!-- START: Footer -->
        <?php include "../mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>

    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->


</body>

</html>