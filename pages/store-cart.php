<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>GoodGames | Cart</title>

    <meta name="description" content="GoodGames - Bootstrap template for communities and games store">
    <meta name="keywords" content="game, gaming, template, HTML template, responsive, Bootstrap, premium">
    <meta name="author" content="_nK">

    <link rel="icon" type="image/png" href="../assets/images/favicon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- START: Styles -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7cOpen+Sans:400,700" rel="stylesheet" type="text/css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <script defer src="../assets/vendor/fontawesome-free/js/all.js"></script>
    <script defer src="../assets/vendor/fontawesome-free/js/v4-shims.js"></script>

    <!-- IonIcons -->
    <link rel="stylesheet" href="../assets/vendor/ionicons/css/ionicons.min.css">

    <!-- Flickity -->
    <link rel="stylesheet" href="../assets/vendor/flickity/dist/flickity.min.css">

    <!-- Photoswipe -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/photoswipe/dist/photoswipe.css">
    <link rel="stylesheet" type="text/css" href="../assets/vendor/photoswipe/dist/default-skin/default-skin.css">

    <!-- Seiyria Bootstrap Slider -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap-slider/dist/css/bootstrap-slider.min.css">

    <!-- Summernote -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/summernote/dist/summernote-bs4.css">

    <!-- GoodGames -->
    <link rel="stylesheet" href="../assets/css/goodgames.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/custom.css">

    <!-- END: Styles -->

    <!-- jQuery -->
    <script src="../assets/vendor/jquery/dist/jquery.min.js"></script>


</head>

<body>
    <!-- START: Navbar -->
    <?php include '../mod/navbar.php' ?>
    <!-- END: Navbar -->
    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">


                <li><a href="index.html">Home</a></li>


                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="store.html">Store</a></li>


                <li><span class="fa fa-angle-right"></span></li>

                <li><span>Cart</span></li>

            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->




        <div class="container">
            <div class="nk-store nk-store-cart">
                <div class="table-responsive">

                    <!-- START: Products in Cart -->
                    <table class="table nk-store-cart-products">
                        <tbody>

                            <tr>
                                <td class="nk-product-cart-thumb">
                                    <a href="store-product.html" class="nk-image-box-1 nk-post-image">
                                        <img src="../assets/images/product-2-xs.jpg" alt="However, I have reason" width="115">
                                    </a>
                                </td>
                                <td class="nk-product-cart-title">
                                    <h5 class="h6">Product:</h5>
                                    <div class="nk-gap-1"></div>

                                    <h2 class="nk-post-title h4">
                                        <a href="store-product.html">However, I have reason</a>
                                    </h2>
                                </td>
                                <td class="nk-product-cart-price">
                                    <h5 class="h6">Price:</h5>
                                    <div class="nk-gap-1"></div>

                                    <strong>€ 32.00</strong>
                                </td>
                                <td class="nk-product-cart-quantity">
                                    <h5 class="h6">Quantity:</h5>
                                    <div class="nk-gap-1"></div>

                                    <div class="nk-form">
                                        <input type="number" class="form-control" value="1" min="1" max="21">
                                    </div>
                                </td>
                                <td class="nk-product-cart-total">
                                    <h5 class="h6">Total:</h5>
                                    <div class="nk-gap-1"></div>

                                    <strong>€ 32.00</strong>
                                </td>
                                <td class="nk-product-cart-remove"><a href="#"><span
                                            class="ion-android-close"></span></a></td>
                            </tr>

                            <tr>
                                <td class="nk-product-cart-thumb">
                                    <a href="store-product.html" class="nk-image-box-1 nk-post-image">
                                        <img src="../assets/images/product-4-xs.jpg" alt="She was bouncing" width="115">
                                    </a>
                                </td>
                                <td class="nk-product-cart-title">
                                    <h5 class="h6">Product:</h5>
                                    <div class="nk-gap-1"></div>

                                    <h2 class="nk-post-title h4">
                                        <a href="store-product.html">She was bouncing</a>
                                    </h2>
                                </td>
                                <td class="nk-product-cart-price">
                                    <h5 class="h6">Price:</h5>
                                    <div class="nk-gap-1"></div>

                                    <strong>€ 20.00</strong>
                                </td>
                                <td class="nk-product-cart-quantity">
                                    <h5 class="h6">Quantity:</h5>
                                    <div class="nk-gap-1"></div>

                                    <div class="nk-form">
                                        <input type="number" class="form-control" value="1" min="1" max="21">
                                    </div>
                                </td>
                                <td class="nk-product-cart-total">
                                    <h5 class="h6">Total:</h5>
                                    <div class="nk-gap-1"></div>

                                    <strong>€ 20.00</strong>
                                </td>
                                <td class="nk-product-cart-remove"><a href="#"><span
                                            class="ion-android-close"></span></a></td>
                            </tr>

                        </tbody>
                    </table>
                    <!-- END: Products in Cart -->

                </div>
                <div class="nk-gap-1"></div>
                <a class="nk-btn nk-btn-rounded nk-btn-color-white float-right" href="#">Update Cart</a>

                <div class="clearfix"></div>
                <div class="nk-gap-2"></div>
                <div class="row vertical-gap">
                    <div class="col-md-6">

                        <!-- START: Calculate Shipping -->
                        <h3 class="nk-title h4">Calculate Shipping</h3>
                        <form action="#" class="nk-form">
                            <label for="state">Address <span class="text-main-1">*</span>:</label>
                            <input type="text" class="form-control required" name="state" id="state">


                            <div class="nk-gap-1"></div>
                            <div class="row vertical-gap">
                                <div class="col-sm-6">
                                    <label for="country-sel">City <span class="text-main-1">*</span>:</label>
                                    <select name="country" class="form-control required" id="country-sel">
                                        <option value="">---Select a city---</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="zip">Discount Voucher <span class="text-main-1">*</span>:</label>
                                    <select name="discount_voucher" class="form-control required" id="discount_voucher">
                                        <option value="">---Select a discount voucher---</option>
                                    </select>
                                </div>
                            </div>

                            <div class="nk-gap-1"></div>
                            <a class="nk-btn nk-btn-rounded nk-btn-color-white float-right" href="#">Update Totals</a>
                        </form>
                        <!-- END: Calculate Shipping -->

                    </div>
                    <div class="col-md-6">
                        <!-- START: Cart Totals -->
                        <h3 class="nk-title h4">Cart Totals</h3>
                        <table class="nk-table nk-table-sm">
                            <tbody>
                                <tr class="nk-store-cart-totals-subtotal">
                                    <td>
                                        Subtotal
                                    </td>
                                    <td>
                                        € 52.00
                                    </td>
                                </tr>
                                <tr class="nk-store-cart-totals-shipping">
                                    <td>
                                        Shipping
                                    </td>
                                    <td>
                                        Free Shipping
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
                        <!-- END: Cart Totals -->
                    </div>
                </div>

                <div class="nk-gap-2"></div>
                <a class="nk-btn nk-btn-rounded nk-btn-color-main-1 float-right" href="store-checkout.html">Proceed to
                    Checkout</a>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="nk-gap-2"></div>



        <!-- START: Footer -->
        <?php include '../mod/footer.php' ?>
        <!-- END: Footer -->


    </div>




    <!-- START: Page Background -->

    <img class="nk-page-background-top" src="../assets/images/bg-top-4.png" alt="">
    <img class="nk-page-background-bottom" src="../assets/images/bg-bottom.png" alt="">

    <!-- END: Page Background -->


    <!-- START: Scripts -->

    <!-- Object Fit Polyfill -->
    <script src="../assets/vendor/object-fit-images/dist/ofi.min.js"></script>

    <!-- GSAP -->
    <script src="../assets/vendor/gsap/src/minified/TweenMax.min.js"></script>
    <script src="../assets/vendor/gsap/src/minified/plugins/ScrollToPlugin.min.js"></script>

    <!-- Popper -->
    <script src="../assets/vendor/popper.js/dist/umd/popper.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Sticky Kit -->
    <script src="../assets/vendor/sticky-kit/dist/sticky-kit.min.js"></script>

    <!-- Jarallax -->
    <script src="../assets/vendor/jarallax/dist/jarallax.min.js"></script>
    <script src="../assets/vendor/jarallax/dist/jarallax-video.min.js"></script>

    <!-- imagesLoaded -->
    <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>

    <!-- Flickity -->
    <script src="../assets/vendor/flickity/dist/flickity.pkgd.min.js"></script>

    <!-- Photoswipe -->
    <script src="../assets/vendor/photoswipe/dist/photoswipe.min.js"></script>
    <script src="../assets/vendor/photoswipe/dist/photoswipe-ui-default.min.js"></script>

    <!-- Jquery Validation -->
    <script src="../assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>

    <!-- Jquery Countdown + Moment -->
    <script src="../assets/vendor/jquery-countdown/dist/jquery.countdown.min.js"></script>
    <script src="../assets/vendor/moment/min/moment.min.js"></script>
    <script src="../assets/vendor/moment-timezone/builds/moment-timezone-with-data.min.js"></script>

    <!-- Hammer.js -->
    <script src="../assets/vendor/hammerjs/hammer.min.js"></script>

    <!-- NanoSroller -->
    <script src="../assets/vendor/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>

    <!-- SoundManager2 -->
    <script src="../assets/vendor/soundmanager2/script/soundmanager2-nodebug-jsmin.js"></script>

    <!-- Seiyria Bootstrap Slider -->
    <script src="../assets/vendor/bootstrap-slider/dist/bootstrap-slider.min.js"></script>

    <!-- Summernote -->
    <script src="../assets/vendor/summernote/dist/summernote-bs4.min.js"></script>

    <!-- nK Share -->
    <script src="../assets/plugins/nk-share/nk-share.js"></script>

    <!-- GoodGames -->
    <script src="../assets/js/goodgames.min.js"></script>
    <script src="../assets/js/goodgames-init.js"></script>
    <script src="../../assets/js/navbar.js"></script>
    <!-- END: Scripts -->



</body>

</html>