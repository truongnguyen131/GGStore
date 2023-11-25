<?php
session_start();
include_once('../mod/database_connection.php');

if (!isset($_SESSION['id_account'])) {
    header("Location: ../Galaxy_Game_Store/login");
}


if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    foreach ($_SESSION['shopping_cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['shopping_cart'][$key]);
            break;
        }
    }
    $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
    header("Location: ../Galaxy_Game_Store/shopping_cart");
}

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
                <div class="nk-gap"></div>
                <li><span>Shopping Cart</span></li>

            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->




        <div class="container">
            <div class="nk-store nk-store-cart">
                <div class="table-responsive">
                    <?php if (isset($_SESSION['shopping_cart'])) { ?>
                        <!-- START: Products in Cart -->
                        <table class="table nk-store-cart-products">
                            <tbody>
                                <?php
                                foreach ($_SESSION["shopping_cart"] as $key => $value) { ?>
                                    <tr id="row-<?= $key ?>">
                                        <td class="nk-product-cart-thumb">
                                            <a href="store-product.html" class="nk-image-box-1 nk-post-image">
                                                <img src="./uploads/<?= $value['image_avt_url'] ?>"
                                                    alt="<?= $value['product_name'] ?>" width="180">
                                            </a>
                                        </td>
                                        <td class="nk-product-cart-title">
                                            <h5 style="color: #dc3545;" class="h6">Product name:</h5>
                                            <div class="nk-gap-1"></div>

                                            <h2 class="nk-post-title h4">
                                                <?= $value['product_name'] ?>
                                            </h2>
                                        </td>
                                        <td class="nk-product-cart-price">
                                            <h5 class="h6" style="color: #dc3545;">Price:</h5>
                                            <div class="nk-gap-1"></div>

                                            <strong id="price-row-<?= $key ?>">
                                                <?= $value['price'] ?>
                                            </strong><i class="fas fa-gem"></i>
                                        </td>
                                        <td class="nk-product-cart-quantity">
                                            <h5 class="h6" style="color: #dc3545;">Quantity:</h5>
                                            <div class="nk-gap"></div>

                                            <div class="nk-form">
                                                <?php
                                                if ($value['type'] == "product") { ?>
                                                    <input type="number" id="<?= $key ?>" class="form-control"
                                                        onchange="update_quantity('<?= $key ?>')" value="<?= $value['quantity'] ?>"
                                                        min="1" max="21">
                                                <?php } else { ?>
                                                    <input type="number" style="background-color: transparent;" readonly id="<?= $key ?>" class="form-control"
                                                        onchange="update_quantity('<?= $key ?>')" value="<?= $value['quantity'] ?>"
                                                        min="1" max="21">
                                                <?php } ?>


                                            </div>
                                        </td>
                                        <td class="nk-product-cart-total">
                                            <h5 class="h6" style="color: #dc3545;">Total:</h5>
                                            <div class="nk-gap-1"></div>

                                            <strong id="total-row-<?= $key ?>">
                                                <?= $value['price'] * $value['quantity'] ?>
                                            </strong><i class="fas fa-gem"></i>
                                        </td>
                                        <td class="nk-product-cart-remove"><a
                                                href="../Galaxy_Game_Store/shopping_cart?delete=<?= $value['product_id'] ?>">
                                                <span class="ion-android-close"></span></a></td>
                                    </tr>
                                <?php } ?>

                                <script>
                                    function update_quantity(key) {
                                        var newQuantity = document.getElementById(key).value;

                                        $.post('../Galaxy_Game_Store/pages/add_to_cart.php', {
                                            key: key,
                                            newQuantity: newQuantity
                                        }, function (data) { });
                                    }
                                </script>

                                <script>
                                    let quantityInputs = document.querySelectorAll('.form-control');

                                    quantityInputs.forEach(input => {
                                        input.addEventListener('change', handleQuantityChange);
                                    })

                                    function handleQuantityChange(e) {

                                        let productRow = e.target.closest('tr');
                                        let rowId = productRow.id;

                                        let newQuantity = e.target.value;
                                        if (newQuantity <= 0) {
                                            e.target.value = 1;
                                            newQuantity = 1;
                                        }
                                        var price_id = "price-" + rowId;
                                        var price = document.getElementById(price_id).innerText;
                                        var total = newQuantity * price;
                                        var total_id = "total-" + rowId;
                                        document.getElementById(total_id).innerText = total;

                                        const totals = document.querySelectorAll('.nk-product-cart-total');

                                        let grand_total = 0;

                                        totals.forEach(totalEl => {
                                            let value = totalEl.querySelector('strong').innerText;
                                            value = parseInt(value);
                                            grand_total += value;
                                        })

                                        document.getElementById('grand-total').innerText = grand_total;
                                    }
                                </script>

                            </tbody>
                        </table>
                        <table class="nk-table nk-table-sm">
                            <tr class="nk-store-cart-totals-total">
                                <td>
                                    <strong style="color: #dc3545;">TOTAL PRICE OF PRODUCTS:</strong>
                                </td>
                                <td style="text-align: right;">
                                    <span id="grand-total">
                                        <?php
                                        $total_price = array_sum(array_map(function ($item) {
                                            return $item['price'] * $item['quantity'];
                                        }, $_SESSION['shopping_cart']));
                                        echo $total_price;
                                        ?>
                                    </span>
                                    <i class="fas fa-gem"></i>
                                </td>
                            </tr>
                        </table>
                        <!-- END: Products in Cart -->
                    <?php } ?>


                </div>

                <div class="nk-gap-2"></div>
                <a class="nk-btn nk-btn-rounded nk-btn-color-main-1 float-right"
                    href="../Galaxy_Game_Store/checkout">Proceed to
                    Checkout</a>
                <div class="clearfix"></div>
            </div>
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