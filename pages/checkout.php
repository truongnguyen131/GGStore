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
            <?php if (isset($_SESSION['shopping_cart'])) { ?>
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
                                    <div class="row vertical-gap">
                                        <div class="col-sm-6">
                                            <label for="fname">First Name <span class="text-main-1">*</span>:</label>
                                            <input type="text" class="form-control required" name="fname" id="fname">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="lname">Last Name <span class="text-main-1">*</span>:</label>
                                            <input type="text" class="form-control required" name="lname" id="lname">
                                        </div>
                                    </div>

                                    <div class="nk-gap-1"></div>
                                    <label for="company">Company Name:</label>
                                    <input type="text" class="form-control" name="company" id="company">

                                    <div class="nk-gap-1"></div>
                                    <div class="row vertical-gap">
                                        <div class="col-sm-6">
                                            <label for="email">Email Address <span class="text-main-1">*</span>:</label>
                                            <input type="email" class="form-control required" name="email" id="email">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="phone">Phone <span class="text-main-1">*</span>:</label>
                                            <input type="tel" class="form-control required" name="phone" id="phone">
                                        </div>
                                    </div>

                                    <div class="nk-gap-1"></div>
                                    <label for="country-sel">Country <span class="text-main-1">*</span>:</label>
                                    <select name="country" class="form-control required" id="country-sel">
                                        <option value="">Select a country...</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="address">Address <span class="text-main-1">*</span>:</label>
                                    <input type="text" class="form-control required" name="address" id="address">

                                    <div class="nk-gap-1"></div>
                                    <label for="city">Town / City <span class="text-main-1">*</span>:</label>
                                    <input type="text" class="form-control required" name="city" id="city">

                                    <div class="nk-gap-1"></div>
                                    <div class="row vertical-gap">
                                        <div class="col-sm-6">
                                            <label for="state">State / Country <span class="text-main-1">*</span>:</label>
                                            <input type="text" class="form-control required" name="state" id="state">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="zip">Postcode / ZIP <span class="text-main-1">*</span>:</label>
                                            <input type="tel" class="form-control required" name="zip" id="zip">
                                        </div>
                                    </div>

                                    <div class="nk-gap-1"></div>
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        Ship to different address?
                                    </label>
                                </div>
                            </div>
                        </form>
                        <!-- END: Billing Details -->



                        <div class="nk-gap-2"></div>
                        <form action="#" class="nk-form">
                            <div class="nk-gap-1"></div>
                            <label for="notes">Order Notes:</label>
                            <textarea class="form-control" name="notes" id="notes" placeholder="Order Notes"
                                rows="6"></textarea>
                        </form>
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
                            <tbody>
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
                                        $total_price = array_sum(array_map(function ($item) {
                                            return $item['price'] * $item['quantity'];
                                        }, $_SESSION['shopping_cart']));
                                        echo $total_price;
                                        ?><i class="fas fa-gem"></i>
                                    </td>
                                </tr>

                                <tr class="nk-store-cart-totals-shipping">
                                    <td>
                                        Voucher
                                    </td>
                                    <td>
                                        <select name="voucher_id" style="width: 180px;" class="form-control required">
                                            <?php
                                            $user_id = $_SESSION['id_account'];
                                            $sql_sl_voucher = "SELECT  v.*, uv.user_id FROM vouchers v
                                                LEFT JOIN user_voucher uv ON v.id = uv.voucher_id
                                                WHERE v.date_expiry >= NOW() OR v.date_expiry IS NULL
                                                AND uv.user_id = $user_id";

                                            $result_sl_voucher = $conn->query($sql_sl_voucher);
                                            if ($result_sl_voucher->num_rows > 0) {
                                                while ($row_sl_voucher = $result_sl_voucher->fetch_assoc()) { ?>
                                                    <option value="<?php echo $row_sl_voucher["id"]; ?>">
                                                        <?php echo $row_sl_voucher["id"]; ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr class="nk-store-cart-totals-total">
                                    <td>
                                        Total
                                    </td>
                                    <td>
                                        € 52.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- END: Order Products -->

                    <div class="nk-gap-2"></div>
                    <a class="nk-btn nk-btn-rounded nk-btn-color-main-1" href="#">Place Order</a>
                </div>
            <?php } ?>
        </div>
        <div class="nk-gap-2"></div>


        <!-- START: Footer -->
        <?php include "../mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>





    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->



</body>

</html>