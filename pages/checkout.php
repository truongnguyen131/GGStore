<?php
session_start();
include_once('../mod/database_connection.php');

if(isset($_SESSION['buy_now_temp'])){
    unset($_SESSION['buy_now_temp']);
}

$user_id = $_SESSION['id_account'];

$sql = "SELECT Gcoin FROM `users` WHERE id = $user_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$gcoin = $row['Gcoin'];
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

                <li><a href="../Galaxy_Game_Store/shopping_cart">Shopping Cart</a></li>


                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="../Galaxy_Game_Store/checkout">Checkout</a></li>


                <li><span class="fa fa-angle-right"></span></li>
                <div class="nk-gap-1"></div>
                <li><span>Proceed to checkout</span></li>

            </ul>
        </div>
        <div class="nk-gap-3"></div>
        <!-- END: Breadcrumbs -->




        <div class="container">
            <?php
            if (!isset($_SESSION['buy_now'])) {
                if (isset($_SESSION['shopping_cart']) && count($_SESSION['shopping_cart']) > 0) { ?>
                    <div class="nk-store nk-store-checkout">

                        <?php
                        $cart_products = array_column($_SESSION['shopping_cart'], 'product_id');
                        $sql = "SELECT id, classify FROM products 
                            WHERE id IN (" . implode(',', $cart_products) . ")";
                        $result = $conn->query($sql);
                        $has_gear = false;
                        while ($row = $result->fetch_assoc()) {
                            if ($row['classify'] == 'gear') {
                                $has_gear = true;
                                break;
                            }
                        }
                        ?>

                        <?php if ($has_gear) { ?>
                            <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Billing</span> Details</span></h3>

                            <!-- START: Billing Details -->
                            <div class="nk-gap"></div>
                            <form action="#" class="nk-form">
                                <div class="row vertical-gap">
                                    <div class="col-lg-6">
                                        <?php
                                        $sql_info_user = "SELECT * FROM `users` WHERE id = $user_id";
                                        $result_info_user = $conn->query($sql_info_user);
                                        $row_info_user = $result_info_user->fetch_assoc();
                                        ?>

                                        <label for="email">Email Address:</label>
                                        <input type="email" class="form-control required" name="email" id="email" readonly
                                            style="color: #dd163b;background-color: transparent;"
                                            value="<?= $row_info_user['email'] ?>">

                                        <div class="nk-gap-1"></div>
                                        <div class="row vertical-gap">
                                            <div class="col-sm-6">
                                                <label for="full_name">Full Name:</label>
                                                <input type="text" class="form-control required"
                                                    value="<?= $row_info_user['full_name'] ?>" name="full_name" id="full_name"
                                                    readonly style="color: #dd163b;background-color: transparent;">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="phone">Phone Number:</label>
                                                <input type="tel" class="form-control required" name="phone" id="phone"
                                                    value="<?= $row_info_user['phone_number'] ?>" readonly
                                                    style="color: #dd163b;background-color: transparent;">
                                            </div>
                                        </div>

                                        <div class="nk-gap-1"></div>
                                        <label for="address">Address <span class="text-main-1">*</span>:</label>
                                        <input type="text" class="form-control required" placeholder="Number house - Street name"
                                            name="address" id="address">

                                    </div>
                                    <div class="col-lg-6">
                                        <label for="country-sel">Province <span class="text-main-1">*</span>:</label>
                                        <select name="province" onchange="get_delivery_fee()" class="form-control required"
                                            id="province">
                                        </select>

                                        <div class="nk-gap-1"></div>
                                        <label for="city">District <span class="text-main-1">*</span>:</label>
                                        <select name="district" class="form-control required" id="district">
                                            <option value="">--district--</option>
                                        </select>

                                        <div class="nk-gap-2"></div>
                                        <label for="state">Ward <span class="text-main-1">*</span>:</label>
                                        <select name="ward" class="form-control required" id="ward">
                                            <option value="">--ward--</option>
                                        </select>

                                    </div>
                                </div>
                            </form>

                            <!-- MapBox -->
                            <div>

                            </div>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"
                                integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
                                crossorigin="anonymous" referrerpolicy="no-referrer"></script>

                            <script>
                                const host = "https://provinces.open-api.vn/api/";
                                var callAPI = (api) => {
                                    return axios.get(api)
                                        .then((response) => {
                                            renderData(response.data, "province");
                                        });
                                }
                                callAPI('https://provinces.open-api.vn/api/?depth=1');
                                var callApiDistrict = (api) => {
                                    return axios.get(api)
                                        .then((response) => {
                                            renderData(response.data.districts, "district");
                                        });
                                }
                                var callApiWard = (api) => {
                                    return axios.get(api)
                                        .then((response) => {
                                            renderData(response.data.wards, "ward");
                                        });
                                }

                                var renderData = (array, select) => {
                                    let row = ' <option disable value="">--' + select + '--</option>';
                                    array.forEach(element => {
                                        row += `<option value="${element.code}">${element.name}</option>`
                                    });
                                    document.querySelector("#" + select).innerHTML = row
                                }

                                $("#province").change(() => {
                                    callApiDistrict(host + "p/" + $("#province").val() + "?depth=2");
                                });
                                $("#district").change(() => {
                                    callApiWard(host + "d/" + $("#district").val() + "?depth=2");
                                });
                            </script>

                            <!-- END: Billing Details -->

                            <div class="nk-gap-3"></div>
                        <?php } ?>


                        <!-- START: Order Products -->
                        <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Your</span> Order</span></h3>
                        <div class="nk-gap"></div>
                        <div class="table-responsive">
                            <table class="nk-table nk-table-sm">
                                <thead class="thead-default">
                                    <tr>
                                        <th class="nk-product-cart-title">Product</th>
                                        <th class="nk-product-cart-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="checkout_item_scroll">
                                    <?php foreach ($_SESSION["shopping_cart"] as $key => $value) { ?>
                                        <tr>
                                            <td class="nk-product-cart-title">
                                                <?= $value['product_name'] ?> &times;
                                                <?= $value['quantity'] ?>
                                            </td>
                                            <td class="nk-product-cart-total">
                                                <?= $value['price'] * $value['quantity'] ?><i class="fas fa-gem"></i>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr class="nk-store-cart-totals-subtotal">
                                        <td>
                                            Subtotal
                                        </td>
                                        <td>
                                            <?php
                                            $subtotal = array_sum(array_map(function ($item) {
                                                return $item['price'] * $item['quantity'];
                                            }, $_SESSION['shopping_cart']));
                                            echo $subtotal;
                                            ?><i class="fas fa-gem"></i>
                                        </td>
                                    </tr>

                                    <?php if ($has_gear) { ?>
                                        <tr class="nk-store-cart-totals-shipping">
                                            <td>
                                                Delivery fee
                                            </td>
                                            <td>
                                                <span id="delivery_fee">0</span>
                                                <i class="fas fa-gem"></i>
                                            </td>
                                        </tr>
                                        <?php $sql_sl_voucher = "SELECT v.*, uv.user_id FROM vouchers v
                                            INNER JOIN user_voucher uv ON v.id = uv.voucher_id
                                            WHERE uv.user_id = $user_id AND v.type = 'freeship'
                                            AND v.minimum_condition <= $subtotal AND (uv.amount >= 1)
                                            AND (v.date_expiry >= NOW() OR v.date_expiry IS NULL)";

                                        $result_sl_voucher = $conn->query($sql_sl_voucher);
                                        if ($result_sl_voucher->num_rows > 0) { ?>
                                            <tr class="nk-store-cart-totals-shipping">
                                                <td>
                                                    Freeship voucher
                                                </td>
                                                <td>
                                                    <select name="freeship_id" id="freeship_id" class="form-control required"
                                                        onchange="update_total_price()">
                                                        <option value="none">--None----</option>

                                                        <?php while ($row_sl_voucher = $result_sl_voucher->fetch_assoc()) { ?>
                                                            <option value="<?php echo $row_sl_voucher["id"]; ?>">
                                                                <?php
                                                                $string_discount = "Freeship " . $row_sl_voucher["value"] . "Gcoin";

                                                                if ($row_sl_voucher["minimum_condition"] != 0) {
                                                                    $string_discount .= " for minimum order " . $row_sl_voucher["minimum_condition"] . " Gcoin";
                                                                }
                                                                echo $string_discount;
                                                                ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>


                                    <?php
                                    $sql_sl_voucher = "SELECT v.*, uv.user_id FROM vouchers v
                                                    INNER JOIN user_voucher uv ON v.id = uv.voucher_id
                            WHERE uv.user_id = $user_id AND v.type != 'freeship' AND v.minimum_condition <= $subtotal 
                                            AND (uv.amount >= 1) 
                                            AND (v.date_expiry >= NOW() OR v.date_expiry IS NULL)";

                                    $result_sl_voucher = $conn->query($sql_sl_voucher);
                                    if ($result_sl_voucher->num_rows > 0) { ?>
                                        <tr class="nk-store-cart-totals-shipping">
                                            <td>
                                                Voucher
                                            </td>
                                            <td>
                                                <select name="voucher_id" id="voucher_id" class="form-control required"
                                                    onchange="update_total_price()">
                                                    <option value="none">--None----</option>
                                                    <?php
                                                    while ($row_sl_voucher = $result_sl_voucher->fetch_assoc()) { ?>
                                                        <option value="<?php echo $row_sl_voucher["id"]; ?>">
                                                            <?php
                                                            $string_discount = "Discount " . $row_sl_voucher["value"];
                                                            $string_discount .= $row_sl_voucher["type"] == "percent" ? "%" : "Gcoin";

                                                            if ($row_sl_voucher["minimum_condition"] != 0) {
                                                                $string_discount .= " for minimum order " . $row_sl_voucher["minimum_condition"] . " Gcoin";
                                                            }
                                                            echo $string_discount;
                                                            ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr class="nk-store-cart-totals-total">
                                        <td>
                                            Total
                                        </td>
                                        <td>
                                            <span id="total_price">
                                                <?= $subtotal ?>
                                            </span>
                                            <i class="fas fa-gem"></i>
                                        </td>
                                    </tr>

                                    <script>
                                        function update_total_price() {
                                            var freeship_id = 'none';
                                            var voucher_id = 'none';
                                            var has_gear = false;
                                            <?php
                                            if ($has_gear) { ?>
                                                has_gear = true;
                                                var delivery_fee = document.getElementById("delivery_fee").innerText;
                                            <?php } ?>
                                            if (document.getElementById("freeship_id")) {
                                                var freeship_id = document.getElementById("freeship_id").value;
                                            }
                                            if (document.getElementById("voucher_id")) {
                                                var voucher_id = document.getElementById("voucher_id").value;
                                            }
                                            $.post('../Galaxy_Game_Store/pages/update_total_price.php', {
                                                delivery_fee: delivery_fee,
                                                has_gear: has_gear,
                                                freeship_id: freeship_id,
                                                voucher_id: voucher_id
                                            }, function (data) {
                                                $('#total_price').html(data);
                                            });
                                        }
                                    </script>
                                </tbody>
                            </table>
                        </div>
                        <!-- END: Order Products -->

                        <div class="nk-gap-2"></div>
                        <a class="nk-btn nk-btn-rounded nk-btn-color-main-1" href="javascript:place_order()">Place Order</a>
                    </div>
                <?php }
            } else { $_SESSION['buy_now_temp'] = $_SESSION['buy_now']; ?>
                <div class="nk-store nk-store-checkout">

                    <?php $has_gear = false;
                    if ($_SESSION['buy_now'][0]['type'] == "gear") {
                        $has_gear = true; ?>
                        <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Billing</span> Details</span></h3>

                        <!-- START: Billing Details -->
                        <div class="nk-gap"></div>
                        <form action="#" class="nk-form">
                            <div class="row vertical-gap">
                                <div class="col-lg-6">
                                    <?php
                                    $sql_info_user = "SELECT * FROM `users` WHERE id = $user_id";
                                    $result_info_user = $conn->query($sql_info_user);
                                    $row_info_user = $result_info_user->fetch_assoc();
                                    ?>

                                    <label for="email">Email Address:</label>
                                    <input type="email" class="form-control required" name="email" id="email" readonly
                                        style="color: #dd163b;background-color: transparent;"
                                        value="<?= $row_info_user['email'] ?>">

                                    <div class="nk-gap-1"></div>
                                    <div class="row vertical-gap">
                                        <div class="col-sm-6">
                                            <label for="full_name">Full Name:</label>
                                            <input type="text" class="form-control required"
                                                value="<?= $row_info_user['full_name'] ?>" name="full_name" id="full_name"
                                                readonly style="color: #dd163b;background-color: transparent;">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="phone">Phone Number:</label>
                                            <input type="tel" class="form-control required" name="phone" id="phone"
                                                value="<?= $row_info_user['phone_number'] ?>" readonly
                                                style="color: #dd163b;background-color: transparent;">
                                        </div>
                                    </div>

                                    <div class="nk-gap-1"></div>
                                    <label for="address">Address <span class="text-main-1">*</span>:</label>
                                    <input type="text" class="form-control required" placeholder="Number house - Street name"
                                        name="address" id="address">

                                </div>
                                <div class="col-lg-6">
                                    <label for="country-sel">Province <span class="text-main-1">*</span>:</label>
                                    <select name="province" onchange="get_delivery_fee()" class="form-control required"
                                        id="province">
                                    </select>

                                    <div class="nk-gap-1"></div>
                                    <label for="city">District <span class="text-main-1">*</span>:</label>
                                    <select name="district" class="form-control required" id="district">
                                        <option value="">--district--</option>
                                    </select>

                                    <div class="nk-gap-2"></div>
                                    <label for="state">Ward <span class="text-main-1">*</span>:</label>
                                    <select name="ward" class="form-control required" id="ward">
                                        <option value="">--ward--</option>
                                    </select>

                                </div>
                            </div>
                        </form>

                        <!-- MapBox -->
                        <div>

                        </div>

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"
                            integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
                            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

                        <script>
                            const host_bn = "https://provinces.open-api.vn/api/";
                            var callAPI = (api) => {
                                return axios.get(api)
                                    .then((response) => {
                                        renderData(response.data, "province");
                                    });
                            }
                            callAPI('https://provinces.open-api.vn/api/?depth=1');
                            var callApiDistrict = (api) => {
                                return axios.get(api)
                                    .then((response) => {
                                        renderData(response.data.districts, "district");
                                    });
                            }
                            var callApiWard = (api) => {
                                return axios.get(api)
                                    .then((response) => {
                                        renderData(response.data.wards, "ward");
                                    });
                            }

                            var renderData = (array, select) => {
                                let row = ' <option disable value="">--' + select + '--</option>';
                                array.forEach(element => {
                                    row += `<option value="${element.code}">${element.name}</option>`
                                });
                                document.querySelector("#" + select).innerHTML = row
                            }

                            $("#province").change(() => {
                                callApiDistrict(host_bn + "p/" + $("#province").val() + "?depth=2");
                            });
                            $("#district").change(() => {
                                callApiWard(host_bn + "d/" + $("#district").val() + "?depth=2");
                            });
                        </script>

                        <!-- END: Billing Details -->

                        <div class="nk-gap-3"></div>
                    <?php } ?>


                    <!-- START: Order Products -->
                    <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Your</span> Order</span></h3>
                    <div class="nk-gap"></div>
                    <div class="table-responsive">
                        <table class="nk-table nk-table-sm">
                            <thead class="thead-default">
                                <tr>
                                    <th class="nk-product-cart-title">Product</th>
                                    <th class="nk-product-cart-total">Total</th>
                                </tr>
                            </thead>
                            <tbody class="checkout_item_scroll">
                                <?php foreach ($_SESSION["buy_now"] as $key => $value) { ?>
                                    <tr>
                                        <td class="nk-product-cart-title">
                                            <?= $value['product_name'] ?> &times;
                                            <?= $value['quantity'] ?>
                                        </td>
                                        <td class="nk-product-cart-total">
                                            <?= $value['price'] * $value['quantity'] ?><i class="fas fa-gem"></i>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <tr class="nk-store-cart-totals-subtotal">
                                    <td>
                                        Subtotal
                                    </td>
                                    <td>
                                        <?php
                                        $subtotal = array_sum(array_map(function ($item) {
                                            return $item['price'] * $item['quantity'];
                                        }, $_SESSION['buy_now']));
                                        echo $subtotal;
                                        ?><i class="fas fa-gem"></i>
                                    </td>
                                </tr>

                                <?php if ($_SESSION['buy_now'][0]['type'] == "gear") { ?>
                                    <tr class="nk-store-cart-totals-shipping">
                                        <td>
                                            Delivery fee
                                        </td>
                                        <td>
                                            <span id="delivery_fee">0</span>
                                            <i class="fas fa-gem"></i>
                                        </td>
                                    </tr>
                                    <?php $sql_sl_voucher = "SELECT v.*, uv.user_id FROM vouchers v
                                                                INNER JOIN user_voucher uv ON v.id = uv.voucher_id
                                                                WHERE uv.user_id = $user_id AND v.type = 'freeship'
                                                                AND v.minimum_condition <= $subtotal AND (uv.amount >= 1)
                                                                AND (v.date_expiry >= NOW() OR v.date_expiry IS NULL)";

                                    $result_sl_voucher = $conn->query($sql_sl_voucher);
                                    if ($result_sl_voucher->num_rows > 0) { ?>
                                        <tr class="nk-store-cart-totals-shipping">
                                            <td>
                                                Freeship voucher
                                            </td>
                                            <td>
                                                <select name="freeship_id" id="freeship_id" class="form-control required"
                                                    onchange="update_total_price()">
                                                    <option value="none">--None----</option>

                                                    <?php while ($row_sl_voucher = $result_sl_voucher->fetch_assoc()) { ?>
                                                        <option value="<?php echo $row_sl_voucher["id"]; ?>">
                                                            <?php
                                                            $string_discount = "Freeship " . $row_sl_voucher["value"] . "Gcoin";

                                                            if ($row_sl_voucher["minimum_condition"] != 0) {
                                                                $string_discount .= " for minimum order " . $row_sl_voucher["minimum_condition"] . " Gcoin";
                                                            }
                                                            echo $string_discount;
                                                            ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>


                                <?php
                                $sql_sl_voucher = "SELECT v.*, uv.user_id FROM vouchers v
                            INNER JOIN user_voucher uv ON v.id = uv.voucher_id
                            WHERE uv.user_id = $user_id AND v.type != 'freeship' AND v.minimum_condition <= $subtotal 
                            AND (uv.amount >= 1) AND (v.date_expiry >= NOW() OR v.date_expiry IS NULL)";

                                $result_sl_voucher = $conn->query($sql_sl_voucher);
                                if ($result_sl_voucher->num_rows > 0) { ?>
                                    <tr class="nk-store-cart-totals-shipping">
                                        <td>
                                            Voucher
                                        </td>
                                        <td>
                                            <select name="voucher_id" id="voucher_id" class="form-control required"
                                                onchange="update_total_price()">
                                                <option value="none">--None----</option>
                                                <?php
                                                while ($row_sl_voucher = $result_sl_voucher->fetch_assoc()) { ?>
                                                    <option value="<?php echo $row_sl_voucher["id"]; ?>">
                                                        <?php
                                                        $string_discount = "Discount " . $row_sl_voucher["value"];
                                                        $string_discount .= $row_sl_voucher["type"] == "percent" ? "%" : "Gcoin";

                                                        if ($row_sl_voucher["minimum_condition"] != 0) {
                                                            $string_discount .= " for minimum order " . $row_sl_voucher["minimum_condition"] . " Gcoin";
                                                        }
                                                        echo $string_discount;
                                                        ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr class="nk-store-cart-totals-total">
                                    <td>
                                        Total
                                    </td>
                                    <td>
                                        <span id="total_price">
                                            <?= $subtotal ?>
                                        </span>
                                        <i class="fas fa-gem"></i>
                                    </td>
                                </tr>

                                <script>
                                    function update_total_price() {
                                        var freeship_id = 'none';
                                        var voucher_id = 'none';
                                        var has_gear = false;
                                        <?php
                                        if ($has_gear) { ?>
                                            has_gear = true;
                                            var delivery_fee = document.getElementById("delivery_fee").innerText;
                                        <?php } ?>
                                        if (document.getElementById("freeship_id")) {
                                            var freeship_id = document.getElementById("freeship_id").value;
                                        }
                                        if (document.getElementById("voucher_id")) {
                                            var voucher_id = document.getElementById("voucher_id").value;
                                        }
                                        $.post('../Galaxy_Game_Store/pages/update_total_price.php', {
                                            delivery_fee: delivery_fee,
                                            has_gear: has_gear,
                                            freeship_id: freeship_id,
                                            voucher_id: voucher_id
                                        }, function (data) {
                                            $('#total_price').html(data);
                                        });
                                    }
                                </script>
                            </tbody>
                        </table>
                    </div>
                    <!-- END: Order Products -->

                    <div class="nk-gap-2"></div>
                    <a class="nk-btn nk-btn-rounded nk-btn-color-main-1" href="javascript:place_order()">Place Order</a>
                </div>
                <?php unset($_SESSION['buy_now']);
            } ?>

        </div>
        <div class="nk-gap-2"></div>

        <div id="return_process_checkout"></div>

        <!-- START: Footer -->
        <?php include "../mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>

    <script>
        function place_order() {
            var total_price = Number(document.getElementById("total_price").innerText);
            var gcoin = <?= $gcoin ?>;
            var address = '';
            var freeship_id = 'none';
            var voucher_id = 'none';
            if (document.getElementById("freeship_id")) {
                var freeship_id = document.getElementById("freeship_id").value;
            }
            if (document.getElementById("voucher_id")) {
                var voucher_id = document.getElementById("voucher_id").value;
            }

            <?php if ($has_gear) { ?>
                address = document.getElementById("address").value;
                var regex = /^[a-zA-Z0-9\s\/\p{L}]+$/u;
                if (address.length < 5 || address.indexOf(" ") == -1 || !regex.test(address)) {
                    notification_dialog("Failed", "Enter Address!!!");
                    return false;
                }
                var province = document.getElementById("province").value;
                var district = document.getElementById("district").value;
                var ward = document.getElementById("ward").value;
                if (province == "" || district == "" || ward == "") {
                    notification_dialog("Failed", "Enter Address!!!");
                    return false;
                }

                var provinceSelect = document.getElementById("province");
                for (var i = 0; i < provinceSelect.options.length; i++) {
                    var option = provinceSelect.options[i];

                    if (option.selected) {
                        var province = option.innerHTML;
                        break;
                    }
                }

                var districtSelect = document.getElementById("district");
                for (var i = 0; i < districtSelect.options.length; i++) {
                    var option = districtSelect.options[i];

                    if (option.selected) {
                        var district = option.innerHTML;
                        break;
                    }
                }

                var wardSelect = document.getElementById("ward");
                for (var i = 0; i < wardSelect.options.length; i++) {
                    var option = wardSelect.options[i];

                    if (option.selected) {
                        var ward = option.innerHTML;
                        break;
                    }
                }

                var address = address + " " + ward + " " + district + " " + province;
            <?php } ?>


            if (total_price > gcoin) {
                notification_dialog("Failed", "The order exceeds the G-Coin you currently own!!!");
                return false;
            }
            
            $.post('../Galaxy_Game_Store/pages/checkout_process.php', {
                address: address,
                total_price: total_price,
                freeship_id: freeship_id,
                voucher_id: voucher_id
            }, function (data) {
                $('#return_process_checkout').html(data);
            });


        }
    </script>

    <script>
        function get_delivery_fee() {
            var provinceSelect = document.getElementById("province");
            for (var i = 0; i < provinceSelect.options.length; i++) {
                var option = provinceSelect.options[i];

                if (option.selected) {
                    var province_choose = option.innerHTML;
                    break;
                }
            }
            $.post('../Galaxy_Game_Store/pages/get_delivery_fee.php', {
                province_choose: province_choose
            }, function (data) {
                $('#delivery_fee').html(data);
            });

        }
    </script>

    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->

</body>

</html>