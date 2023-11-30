<?php
session_start();
include_once('../mod/database_connection.php');

if (!isset($_SESSION['id_account'])) {
    echo '<script> window.location = "./pages/login.php"; </script>';
} else {
    $user_id = $_SESSION['id_account'];
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../mod/head.php"; ?>

<body>

    <?php include "../mod/nav.php"; ?>

    <?php
    if (isset($_GET['vnp_TransactionStatus']) && $_GET['vnp_TransactionStatus'] == "00") {
        $amount = $_GET['vnp_Amount'] / 100000;

        $sql_update_gcoin = "UPDATE `users` SET `Gcoin`= Gcoin+? WHERE `id`= ?";
        $stmt_update_gcoin = $conn->prepare($sql_update_gcoin);
        if ($stmt_update_gcoin === false) {
            die("Error preparing statement");
        }
        $stmt_update_gcoin->bind_param("ii", $amount, $user_id);
        if ($stmt_update_gcoin->execute()) { ?>
            <script>
                window.addEventListener("load", function () {
                    notification_dialog("Success", "Transaction Order Successful!!!");
                    setTimeout(() => {
                        location.href = "./recharge";
                    }, 2000);
                });
            </script>
        <?php }
    }
    ?>

    <?php

    if (isset($_GET['message']) && $_GET['message'] == "Successful.") {
        $amount = $_GET['amount'] / 1000;

        $sql_update_gcoin = "UPDATE `users` SET `Gcoin`= Gcoin+? WHERE `id`= ?";
        $stmt_update_gcoin = $conn->prepare($sql_update_gcoin);
        if ($stmt_update_gcoin === false) {
            die("Error preparing statement");
        }
        $stmt_update_gcoin->bind_param("ii", $amount, $user_id);
        if ($stmt_update_gcoin->execute()) { ?>
            <script>
                window.addEventListener("load", function () {
                    notification_dialog("Success", "Transaction Order Successful!!!");
                    setTimeout(() => {
                        location.href = "./recharge";
                    }, 2000);
                });
            </script>
        <?php }
    }

    ?>

    <div class="nk-main">

        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">

                <li><a href="../Galaxy_Game_Store/home">Home</a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="../Galaxy_Game_Store/recharge">Recharge</a></li>

                <li><span class="fa fa-angle-right"></span></li>
                <div class="nk-gap"></div>
                <li><span>Recharge</span></li>

            </ul>
        </div>
        <div class="nk-gap-2"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">
            <div class="row vertical-gap">
                <div class="col-lg-12">
                    <div class="btn_tabs">
                        <button class="btn_tab" id="momo"><img style="border-radius: 10%;"
                                src="./assets/images/momo_logo.png" alt>Momo</button>
                        <button class="btn_tab" id="vnpay"><img style="border-radius: 10%;"
                                src="./assets/images/vnpay-logo.png" alt>VNPay</button>
                    </div>
                    <!-- tabs content -->

                    <div class="tabs_content">
                        <div class="content">
                            <div class="exchange_rate_table">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="titles">VND</h3>
                                    </div>
                                    <div class="col">
                                        <h3 class="titles">G-Coin</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="10" class="col">
                                        <input type="checkbox" name="price" id="10" value="10000">
                                        10.000 VND
                                    </label>
                                    <label for="10" class="col">10</label>
                                </div>
                                <div class="row">
                                    <label for="20" class="col">
                                        <input type="checkbox" name="price" id="20" value="20000">
                                        20.000 VND
                                    </label>
                                    <label for="20" class="col">20</label>
                                </div>
                                <div class="row">
                                    <label for="50" class="col">
                                        <input type="checkbox" name="price" id="50" value="50000">
                                        50.000 VND
                                    </label>
                                    <label for="50" class="col">50</label>
                                </div>
                                <div class="row">
                                    <label for="100" class="col">
                                        <input type="checkbox" name="price" id="100" value="100000">
                                        100.000 VND
                                    </label>
                                    <label for="100" class="col">100</label>
                                </div>
                                <div class="row">
                                    <label for="200" class="col">
                                        <input type="checkbox" name="price" id="200" value="200000">
                                        200.000 VND
                                    </label>
                                    <label for="200" class="col">200</label>
                                </div>
                                <div class="row">
                                    <label for="500" class="col">
                                        <input type="checkbox" name="price" id="500" value="500000">
                                        500.000 VND
                                    </label>
                                    <label for="500" class="col">500</label>
                                </div>
                            </div>
                            <div class="transaction_details">
                                <h3 class="titles">Transaction details</h3>
                                <div class="details_item">
                                    <span>Gcoin</span>
                                    <div class="value">
                                        <div id="value_gcoin" class="value_item">0</div>
                                        <div class="value_item"><i class="fas fa-gem"></i></div>
                                    </div>

                                </div>
                                <div class="details_item">
                                    <span>Price</span>
                                    <div class="value">
                                        <div id="value_vnd" class="value_item">0</div>
                                        <div class="value_item">VND</div>
                                    </div>

                                </div>
                                <div class="details_item">
                                    <span>Payment Methods</span>
                                    <span id="payment_methods"></span>
                                </div>
                                <div class="nk-gap"></div>
                                <a href="javascript:Payment_Processing()" class="btn_payment">Payment Processing</a>
                            </div>
                        </div>
                    </div>
                </div>
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

    <script>

        const momo = document.getElementById('momo');
        const vnpay = document.getElementById('vnpay');
        document.getElementById("payment_methods").innerHTML = 'MoMo';
        momo.style.border = '1px solid #dd163b';

        momo.onclick = () => {
            momo.style.border = '1px solid #dd163b';
            vnpay.style.border = '1px solid gray';
            document.getElementById("payment_methods").innerHTML = 'MoMo';
        }
        vnpay.onclick = () => {
            vnpay.style.border = '1px solid #dd163b';
            momo.style.border = '1px solid gray';
            document.getElementById("payment_methods").innerHTML = 'VNPay';
        }


    </script>

    <script>
        const checkboxes = document.querySelectorAll('input[name="price"]');
        const value_gcoin = document.getElementById('value_gcoin');
        const value_vnd = document.getElementById('value_vnd');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                // Uncheck other checkboxes
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
                function formatCurrency(number) {
                    const parts = number.toString().split(".");
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return parts.join(",");
                }
                // Get the value of the checked checkbox
                const checkedCheckbox = document.querySelector('input[name="price"]:checked');

                if (checkedCheckbox) {
                    const value = checkedCheckbox.value;
                    value_vnd.innerHTML = formatCurrency(value);
                    value_gcoin.innerHTML = (value / 1000);
                } else {
                    value_vnd.innerHTML = 0;
                    value_gcoin.innerHTML = 0;
                }
            });
        });
    </script>


    <!-- Payment_Processing -->
    <script>
        function Payment_Processing() {
            const checkboxes = document.querySelectorAll('input[name="price"]');
            var payment_methods = document.getElementById("payment_methods").innerText;
            let isChecked = false;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    var price = checkbox.value;
                    sessionStorage.setItem('price', price);
                    isChecked = true;
                }
            });

            if (!isChecked) {
                notification_dialog("Failed", "Please Choose Price Recharge!!!");
                return false;
            }

            if (payment_methods == "MoMo") {
                location.href = './pages/momo_payment.php';
            }
            if (payment_methods == "VNPay") {
                location.href = './vnpay_php/vnpay_pay.php';
            }

        }
    </script>



</body>

</html>