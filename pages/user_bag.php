<?php
session_start();
include_once('../mod/database_connection.php');

$user_id = $_SESSION['id_account'];
$total_num_rows = 0;
$search_input = isset($_POST["search_input"]) ? $_POST["search_input"] : "";
$type_item = isset($_POST["type_item"]) ? $_POST["type_item"] : "all";
$game_status = isset($_POST["game_status"]) ? $_POST["game_status"] : "all";
$type_voucher = isset($_POST["type_voucher"]) ? $_POST["type_voucher"] : "all";

if (isset($_GET['game'])) {
    $type_item = 'game';
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if ($page == 1) {
    $begin = 0;
} else {
    $begin = ($page * 4) - 4;
}

$nums_game = 0;
$nums_voucher = 0;

if ($type_item == "game" || $type_item == "all") {
    $sql_sl_purchased_products = "SELECT pp.*,p.product_name,p.image_avt_url,p.price FROM `purchased_products` pp 
                                    LEFT JOIN products p ON p.id = pp.product_id
                                WHERE status != 'traded' AND pp.customer_id = $user_id AND p.product_name LIKE '%$search_input%'";
    if ($game_status != "all") {
        $sql_sl_purchased_products .= " AND status = '$game_status'";
    }

    $result_sl_pp = $conn->query($sql_sl_purchased_products);

    $nums_game = $result_sl_pp->num_rows;


}

if ($type_item == "voucher" || $type_item == "all") {
    $sql_sl_user_voucher = "SELECT * FROM `user_voucher` u JOIN vouchers v ON v.id = u.voucher_id
WHERE (v.quantity > 0 OR v.quantity is NULL)  AND (v.date_expiry >= NOW() OR v.date_expiry is NULL) 
AND u.user_id = $user_id";
    if ($type_voucher != "all") {
        $sql_sl_user_voucher .= " AND type = '$type_voucher'";
    }
    $result_sl_uv = $conn->query($sql_sl_user_voucher);

    $nums_voucher = $result_sl_uv->num_rows;

}

if ($nums_voucher >= $nums_game) {
    $total_num_rows = $nums_voucher;
} else {
    $total_num_rows = $nums_game;
}

$total_page = ceil($total_num_rows / 4);
if ($page > $total_page) {
    $page = 1;
    $begin = 0;
}

if ($nums_voucher > 0) {
    $sql_sl_user_voucher .= " LIMIT $begin, 4";
    $result_sl_uv = $conn->query($sql_sl_user_voucher);
}
if ($nums_game > 0) {
    $sql_sl_purchased_products .= " LIMIT $begin, 4";
    $result_sl_pp = $conn->query($sql_sl_purchased_products);
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include "../mod/head.php"; ?>

<body>
    <?php include "../mod/nav.php"; ?>


    <?php if (isset($_GET['cancel_selling'])) {
        $id_pp = $_GET['id_pp'];
        $id_product = $_GET['id_product'];
        $quantity = $_GET['quantity'];

        $check_query = "SELECT * FROM `purchased_products` WHERE product_id = ? AND customer_id = ? AND status = 'not trading'";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt === false) {
            die("Error preparing statement");
        }
        $check_stmt->bind_param("ii", $id_product, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $row_check = $check_result->fetch_assoc();
        $check_stmt->close();
        if ($check_result->num_rows > 0) {
            $id_update = $row_check['id'];
            $sql_update_pp = "UPDATE `purchased_products` SET `quantity`= quantity + $quantity WHERE `id`= $id_update";
            $conn->query($sql_update_pp);
            $sql_delete_pp = "DELETE FROM `purchased_products` WHERE `id`= $id_pp";
            $conn->query($sql_delete_pp);
        } else {
            $sql_cancel_selling = "UPDATE `purchased_products` SET `price`=0 ,`status`='not trading' WHERE `id`= $id_pp";
            $conn->query($sql_cancel_selling);
        }

        echo '<script>
        window.addEventListener("load", function () {
            notification_dialog("Success", "Cancel Selling Product Successful!!!");
            setTimeout(() => {
                location.href = "./bag";
            }, 2000);
        });
            </script>';

    } ?>

    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">
                <li><a href="../Galaxy_Game_Store/home">Home</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><a href="../Galaxy_Game_Store/bag">My Bag</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><span>My bag</span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">

            <!-- START: Products Filter -->
            <div class="nk-gap-2"></div>
            <div class="row vertical-gap">
                <div class="col-lg-12">
                    <form method="post" name="frmSearch">
                        <div class="row vertical-gap">

                            <div class="col-md-3">
                                <select class="form-control" name="type_item" onchange="submit()">
                                    <option class="options-custom" value="all">All Type Item</option>
                                    <option value="game" <?= (($type_item) == 'game') ? 'selected' : ''; ?>>Game
                                    </option>
                                    <option value="voucher" <?= (($type_item) == 'voucher') ? 'selected' : ''; ?>>
                                        Voucher
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3" <?= (($type_item) == 'voucher') ? 'hidden' : ''; ?>>
                                <select class="form-control form-price" name="game_status" onchange="submit()">
                                    <option value="all">All Game Status</option>
                                    <option value="not trading" <?= (($game_status) == 'not trading') ? 'selected' : ''; ?>>Not Tranding</option>
                                    <option value="trading" <?= (($game_status) == 'trading') ? 'selected' : ''; ?>>
                                        Tranding</option>
                                    <option value="review" <?= (($game_status) == 'review') ? 'selected' : ''; ?>>
                                        Approval</option>
                                </select>
                            </div>

                            <div class="col-md-3" <?= (($type_item) == 'game') ? 'hidden' : ''; ?>>
                                <select class="form-control form-price" name="type_voucher" onchange="submit()">
                                    <option value="all">All Type Voucher</option>
                                    <option value="percent" <?= (($type_voucher) == 'percent') ? 'selected' : ''; ?>>
                                        Percentage Discount</option>
                                    <option value="gcoin" <?= (($type_voucher) == 'gcoin') ? 'selected' : ''; ?>>
                                        Discount in Gcoin</option>
                                    <option value="freeship" <?= (($type_voucher) == 'freeship') ? 'selected' : ''; ?>>
                                        Voucher Freeship</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="nk-input-slider-inline">
                                    <input class="form-control" name="search_input" onkeyup="submit()"
                                        value="<?= $search_input ?>" placeholder="Search Product">
                                    </input>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Products Filter -->
            <div class="nk-gap-3"></div>


            <!-- START: Products -->
            <div class="row vertical-gap" id="product">

                <?php
                if ($type_item == "game" || $type_item == "all") {
                    while ($row_sl_pp = $result_sl_pp->fetch_assoc()) { ?>

                        <?php if ($row_sl_pp['status'] == "not trading") { ?>
                            <div class="col-lg-3 col-sm-6">
                                <div class="nk-gallery-item-box item">
                                    <a href="" class="nk-gallery-item item_img ">
                                        <img style="object-fit: cover;" src="./uploads/<?= $row_sl_pp['image_avt_url'] ?>"
                                            alt="<?= $row_sl_pp['product_name'] ?>">
                                    </a>
                                    <div class="control_item">
                                        <div class="infor_item">
                                            <h4 style="color: #dd163b;">
                                                <a href="../Galaxy_Game_Store/product_details?id=<?= $row_sl_pp['product_id'] ?>">
                                                    <?= $row_sl_pp['product_name'] ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="infor_item">
                                            <span>Type: Game</span>
                                        </div>
                                        <div class="infor_item">
                                            <span>Acquire: <a href="">Store</a>, <a href="">Lucky Wheel</a> </span>
                                        </div>
                                        <div class="infor_item">
                                            <span>Usage: <span>Download</span>, <span>Sale</span></span>
                                        </div>
                                        <div class="infor_item">
                                            <span>Quantity: </span><span style="color: #dd163b;">
                                                <input type="number" id="quantity_input_<?= $row_sl_pp['id'] ?>" min="1"
                                                    max="<?= $row_sl_pp['quantity'] ?>"
                                                    style="width: 50px;height: 24px;background-color: transparent; color:#dd163b"
                                                    value="<?= $row_sl_pp['quantity'] ?>">
                                            </span>
                                        </div>
                                        <div class="btn_control mt-5">
                                            <button
                                                onclick="download_product(<?= $row_sl_pp['id'] ?>,<?= $row_sl_pp['quantity'] ?>)"
                                                class="btn">Download</button>
                                            <button class="btn"
                                                onclick="enter_price_for_sale(<?= $row_sl_pp['id'] ?>,'<?= $row_sl_pp['product_name'] ?>',<?= $row_sl_pp['quantity'] ?>,<?= $row_sl_pp['price'] ?>,<?= $row_sl_pp['product_id'] ?>)">Sale</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($row_sl_pp['status'] == "review") { ?>
                            <div class="col-lg-3 col-sm-6">
                                <div class="nk-gallery-item-box item">
                                    <a href="" class="nk-gallery-item">
                                        <img style="object-fit: cover;" src="./uploads/<?= $row_sl_pp['image_avt_url'] ?>" alt="">
                                    </a>
                                    <div class="control_item">
                                        <div class="infor_item processing">
                                            <h4>Pending approval...</h4>
                                            <button class="btn"
                                                onclick="cancel_selling(<?= $row_sl_pp['id'] ?>, <?= $row_sl_pp['product_id'] ?>, <?= $row_sl_pp['quantity'] ?>)">Cancel
                                                Approval</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($row_sl_pp['status'] == "trading") { ?>
                            <div class="col-lg-3 col-sm-6">
                                <div class="nk-gallery-item-box item">
                                    <a href="" class="nk-gallery-item selling">
                                        <img style="object-fit: cover;" src="./uploads/<?= $row_sl_pp['image_avt_url'] ?>" alt="">
                                    </a>
                                    <div class="control_item">
                                        <div class="infor_item processing">
                                            <h4>Selling</h4>
                                            <button class="btn"
                                                onclick="cancel_selling(<?= $row_sl_pp['id'] ?>, <?= $row_sl_pp['product_id'] ?>, <?= $row_sl_pp['quantity'] ?>)">Cancel
                                                Selling</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    <?php }
                } ?>

                <?php
                if ($type_item == "voucher" || $type_item == "all") {
                    while ($row_sl_uv = $result_sl_uv->fetch_assoc()) { ?>
                        <div class="col-lg-3 col-sm-6">
                            <div class="nk-gallery-item-box item">
                                <a href="" class="nk-gallery-item">
                                    <img style="object-fit: cover;" src="./uploads/<?php if ($row_sl_uv["type"] == "freeship")
                                        echo "voucher_freeship_img.jpg";
                                    if ($row_sl_uv["type"] == "percent")
                                        echo "voucher_percentage_img.jpg";
                                    if ($row_sl_uv["type"] == "gcoin")
                                        echo "voucher_gcoin_img.jpg";
                                    ?>">
                                </a>
                                <div class="control_item">
                                    <div class="infor_item">
                                        <h4 style="color: #dd163b;">
                                            <?php
                                            $string_discount = "Discount " . $row_sl_uv["value"];
                                            $string_discount .= $row_sl_uv["type"] == "percent" ? "%" : "Gcoin";

                                            if ($row_sl_uv["minimum_condition"] != 0) {
                                                $string_discount .= " for minimum order " . $row_sl_uv["minimum_condition"] . " Gcoin";
                                            }
                                            echo $string_discount;
                                            ?>
                                        </h4>
                                    </div>
                                    <div class="infor_item mt-5">
                                        <span>Type: Voucher </span><span>
                                            <?= ($row_sl_uv["type"] == "freeship") ? "Freeship" : "" ?>
                                        </span>
                                    </div>
                                    <div class="infor_item">
                                        <span>Acquire: <a href="">Lucky Wheel</a> </span>
                                    </div>
                                    <div class="infor_item">
                                        <span>Usage: Discount code for purchases</span>
                                    </div>
                                    <div class="infor_item">
                                        <span>Quantity: </span><span style="color: #dd163b;">
                                            <?= $row_sl_uv['amount'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>


                <?php
                if ($total_num_rows == 0) { ?>
                    <div class="col-lg-12" style="text-align: center;color: #dd163b;font-weight: bold;">No results found
                    </div>
                <?php } ?>
            </div>
            <!-- END: Products -->


            <!-- START : sale dialog -->
            <div class="sale_dialog" id="sale_dialog">
                <div class="sale_dialog_content">
                    <div class="name_product_sale">
                        Product Name: <span id="sale_dialog__product_name"
                            style="color: #dd163b; font-size: 1.7em;"></span>
                    </div>
                    <div class="nk-gap"></div>
                    <div class="price_sale">
                        <div class="input_price my-1">
                            <input id="sale_dialog__inputPrice" type="number" placeholder="Price">
                        </div>
                        <div class="erro" style="display: none;">The selling price cannot be lower than the proposed
                            price.</div>
                        <span class="recommended_price ">Recommended price: <span
                                id="sale_dialog__recommended_price"></span> <i class="fas fa-gem"></i></span>
                    </div>
                    <div class="control_sale mt-15">
                        <button id="btn_sale" onclick="show_receipt()">Sale</button>
                        <button id="btn_cancel">Cancel</button>
                    </div><br>
                    <div class="note">
                        Note:
                        <span>The price you sell cannot be lower than the suggested price</span>
                    </div>
                </div>
            </div>

            <div class="sale_accept" id="sale_accept">
                <div class="title">
                    <h4>Transaction Receipt</h4>
                </div>
                <div class="saler">
                    <span>Seller: <span id="seller"></span></span>
                </div>

                <div class="product">
                    <span>Product: <span id="sale_accept__nameProduct"></span></span>
                </div>

                <div class="amout">
                    <span>Amout: <span id="sale_accept_amoutProduct"></span></span>
                </div>

                <div class="price_sale_product">
                    <span>Price: <span id="sale_accept__price"></span> <i class="fas fa-gem"></i></span>
                </div>
                <div class="nk-gap"></div>
                <div class="sale_accept__btn">
                    <button id="sale_accept__btnAccept" onclick="submit_request()">Accept</button>
                    <button id="sale_accept__btnCancel">Cancel</button>
                </div><br>
                <div class="note">
                    Note:
                    <span>Please review the information carefully before pressing the 'Accept' button to submit your
                        request for transaction approval.</span>
                </div>
            </div>
            <!-- END : sale dialog -->

            <!-- START: Pagination -->
            <div class="nk-gap-2"></div>
            <div class="nk-pagination nk-pagination-center">
                <a href='javascript:page_user_bag("bag?page=<?= ($page > 1) ? $page - 1 : $page; ?>")'
                    class="nk-pagination-prev">
                    <span class="ion-ios-arrow-back"></span>
                </a>
                <nav>
                    <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                        <a class="<?= ($i == $page) ? "nk-pagination-current" : "" ?>"
                            href='javascript:page_user_bag("bag?page=<?= $i ?>")'>
                            <?= $i ?>
                        </a>
                    <?php } ?>
                </nav>
                <a href='javascript:page_user_bag("bag?page=<?= ($page < $total_page) ? $page + 1 : $page ?>")'
                    class="nk-pagination-next">
                    <span class="ion-ios-arrow-forward"></span>
                </a>
            </div>
            <!-- END: Pagination -->


        </div>

        <div class="nk-gap-2"></div>



        <!-- START: Footer -->
        <?php include "../mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>


    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->

    <div id="return_post"></div>
</body>

<!-- declare variables -->
<script>
    const open__SaleAccept = document.getElementById('btn_sale');
    const close__SaleAccept = document.getElementById('sale_accept__btnCancel');
    const close__SaleDialog = document.getElementById('btn_cancel');
    // sale dialog
    const sale_dialog = document.getElementById('sale_dialog');
    const sale_dialog__product_name = document.getElementById('sale_dialog__product_name');
    const sale_dialog__inputPrice = document.getElementById('sale_dialog__inputPrice');
    const sale_dialog__recommended_price = document.getElementById('sale_dialog__recommended_price');
    const sale_dialog__amout_product = document.getElementById('sale_dialog__amout_product');
    // sale accept
    const sale_accept = document.getElementById('sale_accept');
    const sale_accept__nameProduct = document.getElementById('sale_accept__nameProduct');
    const sale_accept__price = document.getElementById('sale_accept__price');

    var ID_PP = 0;
    var ID_PD = 0;
    var Quantity_PP = 0;
</script>

<!-- cancel_selling -->
<script>
    function cancel_selling(id_pp, id_product, quantity) {
        var url = "bag?cancel_selling&id_pp=" + id_pp + "&id_product=" + id_product + "&quantity=" + quantity;
        location.href = url;
    }
</script>

<!-- pagination -->
<script>
    function page_user_bag(url) {
        document.frmSearch.action = url;
        document.frmSearch.submit();
    }
</script>

<!-- dialog processing -->
<script>
    function enter_price_for_sale(id_pp, name, max_quantity, price, id_product) {
        ID_PP = id_pp;
        ID_PD = id_product;
        Quantity_PP = max_quantity;
        var id_input = "quantity_input_" + id_pp;
        var input_value = document.getElementById(id_input).value;
        if (input_value > max_quantity || input_value <= 0) {
            notification_dialog("Failed", "Quantity Invalid!!!");
            return false;
        } else {
            event.stopPropagation();
            sale_dialog.style.display = 'block';
            name = name + " x" + input_value;
            sale_dialog__product_name.innerText = name;
            var recommended_price = Math.round((price * input_value) - (price * input_value * 0.2));
            sale_dialog__recommended_price.innerText = recommended_price;
            sale_accept_amoutProduct.innerText = input_value;
        }
    }

    close__SaleDialog.onclick = function () {
        sale_dialog.style.display = 'none';
    };

    function show_receipt() {
        if (sale_dialog__inputPrice.value < Number(sale_dialog__recommended_price.innerText)) {
            notification_dialog("Failed", "Price Invalid!!!");
            return false;
        } else {
            event.stopPropagation();
            sale_accept.style.display = 'block';
            const nameProduct = sale_dialog__product_name.innerText.split("x")[0];
            sale_accept__nameProduct.innerText = nameProduct;
            sale_accept__price.innerText = sale_dialog__inputPrice.value;
            document.getElementById("seller").innerText = "<?= $_SESSION['userName'] ?>";
        }
    }


    close__SaleAccept.onclick = function () {
        sale_accept.style.display = 'none';
    };

</script>

<!-- download product -->
<script>
    function download_product(id, max_quantity) {
        var id_input = "quantity_input_" + id;
        var input_value = document.getElementById(id_input).value;
        if (input_value > max_quantity || input_value <= 0) {
            notification_dialog("Failed", "Quantity Invalid!!!");
        }
        $.post('../Galaxy_Game_Store/pages/download_game.php', { quantity: input_value, id_pp: id }, function (data) {
            $('#return_post').html(data);
        });
    }
</script>

<!-- submit request -->
<script>
    function submit_request() {
        var price = sale_dialog__inputPrice.value;
        var amount = sale_accept_amoutProduct.innerText;

        $.post('../Galaxy_Game_Store/pages/send_request_approval.php', { id_pp: ID_PP, id_product: ID_PD, price: price, amount: amount, quantity_PP: Quantity_PP }, function (data) {
            $('#return_post').html(data);
        });
    }
</script>

</html>