<?php
include_once('../../../mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administrator management</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="/Galaxy_Game_Store/admin/pages/template/dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">GGS Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/Galaxy_Game_Store/admin/pages/template/dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Function -->
            <div class="sidebar-heading">
                <i class="fas fa-fw fa-cog"></i> Function list
            </div>

            <!-- Nav Item - User Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    <span>User Management</span>
                </a>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="../userMangement/change_password.php">Change password</a>
                        <a class="collapse-item" href="../userMangement/users_management.php">Users Management</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Product Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <span>Product Management</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="../productManagement/products_management.php">Products
                            Management</a>
                        <a class="collapse-item" href="../productManagement/product_comments.php">Product
                            Comments</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Discount Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"
                    aria-expanded="true" aria-controls="collapseFour">
                    <span>Discount Management</span>
                </a>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="../discountManagement/discounts_Management.php">Discounts
                            Management</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Order Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive"
                    aria-expanded="true" aria-controls="collapseFive">
                    <span>Order Management</span>
                </a>
                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="../orderManagement/orders_management.php">Orders Management</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Exchange Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix"
                    aria-expanded="true" aria-controls="collapseSix">
                    <span>Exchange Management</span>
                </a>
                <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="">Change password</a>
                        <a class="collapse-item" href="">Users Management</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - News Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTen"
                    aria-expanded="true" aria-controls="collapseTen">
                    <span>News Management</span>
                </a>
                <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="../newsManagement/news_management.php">News Management</a>
                        <a class="collapse-item" href="../newsManagement/news_categories.php">News Categories</a>
                        <a class="collapse-item" href="../newsManagement/news_comments.php">News Comments</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Genre Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="true" aria-controls="collapseThree">
                    <span>Genre Management</span>
                </a>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="../genreManagement/genres_management.php">Genres Management</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Voucher Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeven"
                    aria-expanded="true" aria-controls="collapseSeven">
                    <span>Voucher Management</span>
                </a>
                <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Management components:</h6>
                        <a class="collapse-item" href="../voucherManagement/vouchers_management.php">Vouchers
                            Management</a>
                        <a class="collapse-item" href="../voucherManagement/user_voucher.php">User's Voucher</a>
                        <a class="collapse-item" href="../voucherManagement/voucher_usage.php">Voucher Usage</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Backup_file and Export report -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinal"
                    aria-expanded="true" aria-controls="collapseFinal">
                    <span>Backup and Report</span>
                </a>
                <div id="collapseFinal" class="collapse" aria-labelledby="headingFinal" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Function Components:</h6>
                        <a class="collapse-item" href="../backup/backup.php">Backup file</a>
                        <a class="collapse-item" href="">Export report</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand topbar mb-4 static-top shadow" style="background-color: #7496f7c2;">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Home -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link" href="../../../home">
                                <i class="fas fa-home home-icon"></i>
                            </a>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <?php
                                if (isset($_SESSION['notifications'])) {
                                    $notificationCount = count($_SESSION['notifications']);
                                    echo '<span class="badge badge-danger badge-counter">' . $notificationCount . '</span>';
                                } else {
                                    echo '<span class="badge badge-danger badge-counter">0</span>';
                                }
                                ?>

                            </a>
                            <?php
                            if (isset($_SESSION['notifications'])) {
                                foreach ($_SESSION['notifications'] as $key => $notification) {
                                    if ($notification['status'] == "undisplayed") { ?>
                                        <script>
                                            window.addEventListener('DOMContentLoaded', function () {
                                                document.getElementById("alertsDropdown").click();
                                            });
                                        </script>
                                        <?php
                                        $_SESSION['notifications'][$key]['status'] = "displayed";
                                    }
                                }
                                ?>

                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="alertsDropdown">
                                    <h6 class="dropdown-header">
                                        Alerts Center
                                    </h6>
                                    <?php
                                    $reversedNotifications = array_reverse($_SESSION['notifications']);
                                    $lastThreeNotifications = array_slice($reversedNotifications, 0, 3);
                                    foreach ($lastThreeNotifications as $notification) {
                                        $message = $notification['message'];
                                        $type = $notification['type'];
                                        $time = $notification['time'];
                                        if ($type == "error") { ?>
                                            <a class="dropdown-item d-flex align-items-center" href="#"
                                                onclick="delete_alert('<?php echo $time; ?>')">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-warning">
                                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="small text-gray-500">
                                                        <?php echo $time; ?>
                                                    </div>
                                                    <span class="font-weight-bold">
                                                        <?php echo $message; ?>
                                                    </span>
                                                </div>
                                            </a>
                                        <?php } else { ?>
                                            <a class="dropdown-item d-flex align-items-center" href="#"
                                                onclick="delete_alert('<?php echo $time; ?>')">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-primary">
                                                        <i class="fas fa-file-alt text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="small text-gray-500">
                                                        <?php echo $time; ?>
                                                    </div>
                                                    <span class="font-weight-bold">
                                                        <?php echo $message; ?>
                                                    </span>
                                                </div>
                                            </a>
                                        <?php }
                                    }
                                    ?>

                                    <a class="dropdown-item text-center small text-gray-500" onclick="delete_alert('all')"
                                        href="#">Delete Alerts</a>
                                </div>

                            <?php }
                            ?>

                        </li>
                        <div id="delete_notifications"></div>
                        <script>
                            function delete_alert(time) {
                                $.post('../delete_notifications.php', {
                                    time: time
                                }, function (data) {
                                    $('#delete_notifications').html(data);
                                })
                            }
                        </script>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION["userName"]; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="../../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../userMangement/profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?php main(); ?>

                <!-- End of Main Content -->

            </div>


            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; GGS Admin</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../../pages/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Bnt Previous and Next-->
    <script src="../../js/btn_pre_next.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="../vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script> -->

</body>

</html>