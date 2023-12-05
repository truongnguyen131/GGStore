<?php
session_start();
include_once('../mod/database_connection.php');
$user_id = $_SESSION['id_account'];
$from_date = isset($_POST["from_date"]) ? $_POST["from_date"] : "";
$to_date = isset($_POST["to_date"]) ? $_POST["to_date"] : "";
$status = isset($_POST["status"]) ? $_POST["status"] : "all";
$search = isset($_POST["search"]) ? $_POST["search"] : "";
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

                <li><a href="../Galaxy_Game_Store/home">Home</a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="../Galaxy_Game_Store/orders">Orders</a></li>

                <li><span class="fa fa-angle-right"></span></li>
                <div class="nk-gap-1"></div>
                <li><span>Order Details</span></li>

            </ul>
        </div>
        <!-- END: Breadcrumbs -->

        <div class="container">

            <div class="nk-gap-2"></div>

            <div class="row vertical-gap">
                <div class="col-lg-12">
                    <form method="post" name="frmSearch">
                        <div class="row vertical-gap">

                            <div class="col-md-3">
                                <div class="nk-input-slider-inline">
                                    <input type="date" class="form-control" name="from_date" onchange="submit()"
                                        value="<?= $from_date ?>">
                                    </input>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="nk-input-slider-inline">
                                    <input type="date" class="form-control" name="to_date" onchange="submit()"
                                        value="<?= $to_date ?>">
                                    </input>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <select onchange="submit()" name="status"
                                    class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="all">All Status Order</option>
                                    <option value="Waiting for confirmation" <?php echo (($status) == "Waiting for confirmation") ? 'selected' : ''; ?>>Waiting for confirmation</option>
                                    <option value="Waiting for delivery" <?php echo (($status) == "Waiting for delivery") ? 'selected' : ''; ?>>Waiting for delivery</option>
                                    <option value="Paid" <?php echo (($status) == "Paid") ? 'selected' : ''; ?>>
                                        Paid</option>
                                    <option value="Cancelled" <?php echo (($status) == "Cancelled") ? 'selected' : ''; ?>>
                                        Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="nk-input-slider-inline">
                                    <input class="form-control" name="search" onkeyup="submit()" value="<?= $search ?>"
                                        placeholder="Search Product">
                                    </input>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="nk-gap-3"></div>
            <!-- START: News CATEGORY List -->
            <ul class="nk-forum">
                <?php
                $sql_sl_orders = "SELECT * FROM `orders` WHERE customer_id = $user_id";
                if ($status != "all") {
                    $sql_sl_orders .= " AND status = '$status'";
                }
                if ($from_date != "") {
                    $sql_sl_orders .= " AND order_date >= '$from_date'";
                }
                if ($to_date != "") {
                    $to_date_obj = new DateTime($to_date);
                    $to_date_obj->modify('+1 day');
                    $next_day = $to_date_obj->format('Y-m-d H:i:s');
                    $sql_sl_orders .= " AND order_date <= '$next_day'";
                }

                $result_sl_orders = $conn->query($sql_sl_orders);
                if ($from_date != "" && $to_date != "" && $from_date > $to_date) { ?>
                    <div class="col-lg-12" style="text-align: center;color: #dd163b;font-weight: bold;">Error: Start date
                        must be greater than end date
                    </div>

                <?php } else {
                    if ($result_sl_orders->num_rows > 0) {
                        while ($row_sl_orders = $result_sl_orders->fetch_assoc()) { ?>
                            <li>
                                <div class="nk-forum-icon">

                                    <?php if ($row_sl_orders['status'] == 'Paid') { ?>
                                        <span> <i class="fas fa-shopping-cart"></i></span>
                                    <?php } ?>
                                    <?php if ($row_sl_orders['status'] == 'Waiting for delivery') { ?>
                                        <span><i class="fas fa-truck"></i></span>
                                    <?php } ?>
                                    <?php if ($row_sl_orders['status'] == 'Waiting for confirmation') { ?>
                                        <span><i class="fas fa-spinner"></i></span>
                                    <?php } ?>
                                    <?php if ($row_sl_orders['status'] == 'Cancelled') { ?>
                                        <span><i class="fas fa-window-close"></i></span>
                                    <?php } ?>
                                </div>
                                <div class="nk-forum-title">
                                    <h3><a href="javascript:show_order_details(<?= $row_sl_orders['id'] ?>)">
                                            Order No.
                                            <?= $row_sl_orders['id'] ?>
                                        </a></h3>
                                    <div class="nk-forum-title-sub">Order date on
                                        <?= date('M d, Y', strtotime($row_sl_orders['order_date'])) ?>
                                    </div>
                                </div>
                                <div class="nk-forum-count">
                                    <?= $row_sl_orders['total_amount'] ?> <br> Total Amount
                                </div>

                                <div class="nk-forum-activity">
                                    <div class="nk-forum-activity-date">
                                        Status <br>
                                        <?php
                                        if ($row_sl_orders['status'] == "Waiting for confirmation") {
                                            echo "Waiting for approval";
                                        } else {
                                            echo $row_sl_orders['status'];
                                        }
                                        ?>
                                    </div>
                                </div>
                            </li>
                        <?php }
                    } else { ?>
                        <div class="col-lg-12" style="text-align: center;color: #dd163b;font-weight: bold;">No results found
                        </div>
                    <?php }
                } ?>
            </ul>
            <!-- END: News CATEGORY List -->

            <div class="nk-gap-2"></div>

            <!-- START : dialog -->
            <div class="sale_accept" id="sale_accept">

            </div>
            <!-- END : dialog -->

        </div>

        <div class="nk-gap-3"></div>

        <!-- START: Footer -->
        <?php include "../mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>
    <div id="return_cancel_order"></div>

    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->

    <script>

        function show_order_details(order_id) {
            document.getElementById("sale_accept").style.display = 'block';
            $.post('pages/show_order_details.php', { order_id: order_id }, function (data) {
                $('#sale_accept').html(data);
            });
        }

        function cancel_order(order_id) {
            $.post('pages/cancel_order.php', { order_id: order_id }, function (data) {
                $('#return_cancel_order').html(data);
            });
        }

        function close_dialog() {
            document.getElementById('sale_accept').style.display = 'none';
        }



    </script>


</body>

</html>