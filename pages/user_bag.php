<?php
session_start();
include_once('./mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include "./mod/head.php"; ?>
<link rel="stylesheet" href="./assets/css/grand_custom.css">
<link rel="stylesheet" href="./assets/css/new.css">
<body>
    <?php include "./mod/nav.php"; ?>

    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">
                <li><a href="index.html">Home</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><a href="store.html">Store</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><span>Action Games</span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">
            <!-- START: Categories -->
            <div class="row vertical-gap">
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="assets/images/icon-mouse.png" alt="">
                        </div>
                        <div class="nk-feature-cont">
                            <h3 class="nk-feature-title"><a href="#">PC</a></h3>
                            <h3 class="nk-feature-title text-main-1"><a href="#">View Games</a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="assets/images/icon-gamepad.png" alt="">
                        </div>
                        <div class="nk-feature-cont">
                            <h3 class="nk-feature-title"><a href="#">PS4</a></h3>
                            <h3 class="nk-feature-title text-main-1"><a href="#">View Games</a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="assets/images/icon-gamepad-2.png" alt="">
                        </div>
                        <div class="nk-feature-cont">
                            <h3 class="nk-feature-title"><a href="#">Xbox</a></h3>
                            <h3 class="nk-feature-title text-main-1"><a href="#">View Games</a></h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Categories -->

            <!-- START: Products Filter -->
            <div class="nk-gap-2"></div>
            <div class="row vertical-gap">
                <div class="col-lg-12">
                    <div class="row vertical-gap">
                        <div class="col-md-3">
                            <select class="form-control" onchange="filter()">
                                <option class="options-custom" value="all_type">All Type Item</option>
                                <option value="game">Game</option>
                                <option value="gear">Voucher</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-price" onchange="filter()">
                                <option value="all_status">All Item Status</option>
                                <option value="not_trading">Not Tranding</option>
                                <option value="tranding">Tranding</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="nk-input-slider-inline">
                                <input class="form-control" id="search_input" placeholder="Search Product" onkeyup="filter()">
                                </input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Products Filter -->
            <div class="nk-gap-3"></div>
            <!-- START: Products -->
            <div class="row vertical-gap" id="product">
                <div class="col-lg-3 col-sm-6">
                    <div class="nk-gallery-item-box item">
                        <a href="" class="nk-gallery-item item_img ">
                            <img src="./uploads/w_avt.jpg" alt="">
                        </a>
                        <div class="control_item">
                            <div class="infor_item">
                                <h4>Call of Duty</h4>
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
                                <span>Quantity: 1</span>
                            </div>
                            <div class="btn_control">
                                <a href="" class="btn">Download</a>
                                <button class="btn" onclick="open_saleDialog('Call of Duty',5,2)">Sale</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="nk-gallery-item-box item">
                        <a href="" class="nk-gallery-item">
                            <img src="./uploads/n_avt.jpg" alt="">
                        </a>
                        <div class="control_item">
                            <div class="infor_item processing">
                                <h4>Pending approval...</h4>
                                <button class="btn">Cancel Approval</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="nk-gallery-item-box item">
                        <a href="" class="nk-gallery-item selling">
                            <img src="./uploads/n_avt.jpg" alt="">
                        </a>
                        <div class="control_item">
                            <div class="infor_item processing">
                                <h4>Selling</h4>
                                <button class="btn">Cancel Selling</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="nk-gallery-item-box item">
                        <a href="" class="nk-gallery-item">
                            <img src="./uploads/voucher.png" alt="">
                        </a>
                        <div class="control_item">
                            <div class="infor_item">
                                <h4>20% off orders over 100</h4>
                            </div>
                            <div class="infor_item">
                                <span>Type: Voucher</span>
                            </div>
                            <div class="infor_item">
                                <span>Acquire: <a href="">Lucky Wheel</a> </span>
                            </div>
                            <div class="infor_item">
                                <span>Usage: Discount code for purchases</span>
                            </div>
                            <div class="infor_item">
                                <span>Quantity: 1</span>
                            </div>
                            <!-- <div class="btn_control">
                                <a href="" class="btn">Download</a>
                                <button class="btn">Sale</button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Products -->
            <!-- START : sale dialog -->
            <div class="sale_dialog" id="sale_dialog">
                <div class="sale_dialog_content">
                    <div class="name_product_sale">
                        <span>Product name:</span>
                        <span id="sale_dialog__product_name"></span>
                    </div>
                    <div class="saler">
                        <span>Saler:</span>
                        <span>Trường</span>
                    </div>
                    <div class="price_sale">
                        <div class="input_price">
                            <input id="sale_dialog__inputPrice" type="number" placeholder="Price">
                        </div>
                        <div class="erro" style="display: none;">The selling price cannot be lower than the proposed price.</div>
                        <span class="recommended_price">Recommended price: <span id="sale_dialog__recommended_price"></span> <i class="fas fa-gem"></i></span>
                    </div>
                    <div class="amout">
                        <input id="sale_dialog__amout_product"  type="number" min="1" name="" id="" placeholder="Quantity">
                    </div>
                    <div class="control_sale">
                        <button id="btn_sale" onclick="open_saleAccept('Call of Duty',2)">Sale</button>
                        <button id="btn_cancel">Cancel</button>
                    </div>
                    <div class="note">
                        Note:
                        <span>The price you sell cannot be lower than the suggested price</span>
                        <span>You will need to pay 5% based on the total sales value to complete the transaction</span>
                    </div>
                </div>
            </div>
            <div class="sale_accept" id="sale_accept">
                  <div class="title">
                    <h4>Transaction Receipt</h4>
                  </div>
                  <div class="saler">
                    <span>Saler: Truong</span>
                  </div>   
                  <div class="product">
                    <span>Product: <span id="sale_accept__nameProduct"></span></span>
                  </div>
                  <div class="price_sale_product">
                    <span>Price: <span id="sale_accept__price"></span> <i class="fas fa-gem"></i></span>
                  </div>
                  <div class="amout">
                    <span>Amout: <span id="sale_accept_amoutProduct"></span></span>
                  </div>  
                  <div class="total">
                    <span>Total: <span id="sale_accept_total"></span> <i class="fas fa-gem"></i></span>
                  </div>
                  <div class="transaction_fee">
                    <span>Transaction fee: <span id="sale_accept__transaction_fee"></span> <i class="fas fa-gem"></i></span>
                  </div> 
                  <div class="sale_accept__btn">
                    <button id="sale_accept__btnAccept">Accept</button>
                    <button id="sale_accept__btnCancel">Cancel</button>
                  </div>
                  <div class="note">
                    Note:
                    <span>Please review the information before pressing the 'Accept' button to complete the transaction.</span>
                  </div>
            </div>
            <!-- END : sale dialog -->

            <!-- START: Pagination -->
            <div class="nk-gap-3"></div>
            <div class="nk-pagination nk-pagination-center">
                <a href="#" class="nk-pagination-prev">
                    <span class="ion-ios-arrow-back"></span>
                </a>
                <nav>
                    <a class="nk-pagination-current" href="#">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <span>...</span>
                    <a href="#">14</a>
                </nav>
                <a href="#" class="nk-pagination-next">
                    <span class="ion-ios-arrow-forward"></span>
                </a>
            </div>
            <!-- END: Pagination -->


        </div>

        <div class="nk-gap-2"></div>



        <!-- START: Footer -->

        <!-- END: Footer -->


    </div>


    <!-- START: Scripts -->
    <?php include "./mod/add_script.php"; ?>
    <!-- END: Scripts -->


</body>
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
    const sale_accept_amoutProduct = document.getElementById('sale_accept_amoutProduct');
    const sale_accept_total = document.getElementById('sale_accept_total');
    const sale_accept__transaction_fee = document.getElementById('sale_accept__transaction_fee');
    var total;
    var fee;

    function open_saleDialog(name , recommended_prices, amout){
        event.stopPropagation();
        sale_dialog.style.display = 'block';
        sale_dialog__product_name.innerText = name;
        sale_dialog__recommended_price.innerText = recommended_prices;
        amout_product.value = amout;
    }

    close__SaleDialog.onclick = function() {
        sale_dialog.style.display = 'none';
    };

    function open_saleAccept(name){
        event.stopPropagation();
        sale_accept.style.display = 'block';
        total = sale_dialog__inputPrice.value * sale_dialog__amout_product.value;
        fee = total * (5/100);
        sale_accept__nameProduct.innerText = name;
        sale_accept__price.innerText = sale_dialog__inputPrice.value;
        sale_accept_amoutProduct.innerText = sale_dialog__amout_product.value;
        sale_accept_total.innerText = total;
        sale_accept__transaction_fee.innerText = Math.round(fee);
    }

    close__SaleAccept.onclick = function() {
        sale_accept.style.display = 'none';
    };

    // document.addEventListener('click', function(event) {
    //     const close = sale_accept.contains(event.target);
    //     if (sale_dialog.style.display === 'block' && !close) {
    //         sale_dialog.style.display = 'none';
    //     }
    // });
    // document.addEventListener('click', function(event) {
    //     const isClickInside = sale_accept.contains(event.target);
    //     if (sale_accept.style.display === 'block' && !isClickInside) {
    //         sale_accept.style.display = 'none';
    //     }
    // });
</script>

</html>