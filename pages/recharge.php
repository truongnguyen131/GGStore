<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>GoodGames | Recharge</title>

        <meta name="description" content="GoodGames - Bootstrap template for communities and games store">
        <meta name="keywords" content="game, gaming, template, HTML template, responsive, Bootstrap, premium">
        <meta name="author" content="_nK">

        <link rel="icon" type="image/png" href="../assets/images/favicon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- START: Styles -->

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7cOpen+Sans:400,700" rel="stylesheet"
            type="text/css">

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
<?php include'../mod/navbar.php'?>
<!-- END: Navbar -->
        <div class="nk-main">

            <!-- START: Breadcrumbs -->
            <div class="nk-gap-1"></div>
            <div class="container">
                <ul class="nk-breadcrumbs">

                    <li><a href="index.html">Home</a></li>

                    <li><span class="fa fa-angle-right"></span></li>

                    <li><span>Recharge</span></li>

                </ul>
            </div>
            <div class="nk-gap-1"></div>
            <!-- END: Breadcrumbs -->

            <div class="container">
                <div class="row vertical-gap">
                    <div class="col-lg-12">
                        <div class="btn_tabs">
                            <button class="btn_tab" id="phone"><img src="../assets/images/card_phone.png" alt>
                                Phone Card
                            </button>
                            <button class="btn_tab" id="momo"><img src="../assets/images/momo.png" alt>Momo</button>
                        </div>
                        <!-- tabs content -->
                        <div class="tabs_content" id="tab_phone" style="display: none;">
                            <select name id>
                                <option value>Viettel</option>
                                <option value>VinaPhone</option>
                                <option value>Mobifone</option>
                            </select>
                            <div class="content">
                                <div class="exchange_rate_table">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="title">VND</h3>
                                        </div>
                                        <div class="col">
                                            <h3 class="title">G-Coin</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            10.000 VND
                                        </div>
                                        <div class="col">20</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            20.000 VND
                                        </div>
                                        <div class="col">40</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            50.000 VND
                                        </div>
                                        <div class="col">100</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            100.000 VND
                                        </div>
                                        <div class="col">200</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            200.000 VND
                                        </div>
                                        <div class="col">400</div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            500.000 VND
                                        </div>
                                        <div class="col">1000</div>
                                    </div>
                                </div>
                                <div class="enter_information">
                                    <h3 class="title">Fill in card information</h3>
                                    <div class="input">
                                        <span>Card recharge code:</span>
                                        <input type="text" placeholder="Code">
                                    </div>
                                    <a href="#">Agree to exchange</a>
                                </div>
                            </div>
                        </div>
                        <div class="tabs_content" id="tab_momo" style="display: none;">
                            <div class="content">
                                <div class="exchange_rate_table">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="title">VND</h3>
                                        </div>
                                        <div class="col">
                                            <h3 class="title">G-Coin</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="10" class="col">
                                            <input type="checkbox" name="price" id="10" value="10000">
                                            10.000 VND
                                        </label>
                                        <label for="10" class="col">20</label>
                                    </div>
                                    <div class="row">
                                        <label for="20" class="col">
                                            <input type="checkbox" name="price" id="20" value="20000">
                                            20.000 VND
                                        </label>
                                        <label for="20" class="col">40</label>
                                    </div>
                                    <div class="row">
                                        <label for="50" class="col">
                                            <input type="checkbox" name="price" id="50" value="50000">
                                            50.000 VND
                                        </label>
                                        <label for="50" class="col">100</label>
                                    </div>
                                    <div class="row">
                                        <label for="100" class="col">
                                            <input type="checkbox" name="price" id="100" value="100000">
                                            100.000 VND
                                        </label>
                                        <label for="100" class="col">200</label>
                                    </div>
                                    <div class="row">
                                        <label for="200" class="col">
                                            <input type="checkbox" name="price" id="200" value="200000">
                                            200.000 VND
                                        </label>
                                        <label for="200" class="col">400</label>
                                    </div>
                                    <div class="row">
                                        <label for="500" class="col">
                                            <input type="checkbox" name="price" id="500" value="500000">
                                            500.000 VND
                                        </label>
                                        <label for="500" class="col">1000</label>
                                    </div>
                                </div>
                                <div class="transaction_details">
                                    <h3 class="title">Transaction details</h3>
                                    <div class="item">
                                        <span>Selected Product</span>
                                        <div class="value">
                                            <div id="value_gcoin" class="value_item">0</div>
                                            <div class="value_item">G-Coin</div>
                                        </div>

                                    </div>
                                    <div class="item">
                                        <span>Price</span>
                                        <div class="value">
                                            <div id="value_vnd" class="value_item">0</div>
                                            <div class="value_item">VND</div>
                                        </div>

                                    </div>
                                    <div class="item">
                                        <span>Payment Methods</span>
                                        <span>Momo</span>
                                    </div>
                                    <a href="#" class="btn">Payment Processing</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-gap-2"></div>

            <!-- START: Footer -->
            <?php include'../mod/footer.php'?>
            <!-- END: Footer -->

        </div>

        <!-- START: Page Background -->

        <img class="nk-page-background-top" src="../assets/images/bg-top-5.png" alt>
        <img class="nk-page-background-bottom" src="../assets/images/bg-bottom.png" alt>

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
        <script src="../assets/js/navbar.js"></script>
        <!-- END: Scripts -->

        <script>
            const phone = document.getElementById('phone');
            const momo = document.getElementById('momo');
            const tab_phone = document.getElementById('tab_phone');
            const tab_momo = document.getElementById('tab_momo');
            tab_phone.style.display = 'block';
            phone.style.border = '1px solid #dd163b';
            phone.onclick = () => {
                phone.style.border = '1px solid #dd163b';
                momo.style.border = '1px solid gray';
                tab_phone.style.display = 'block';
                tab_momo.style.display = 'none';
            }
            momo.onclick = () => {
                phone.style.border = '1px solid gray';
                momo.style.border = '1px solid #dd163b';
                tab_phone.style.display = 'none';
                tab_momo.style.display = 'block';
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
                    value_vnd.innerHTML =  formatCurrency(value);
                    value_gcoin.innerHTML = (value / 1000) * 2;
                }else{
                    value_vnd.innerHTML =  0;
                    value_gcoin.innerHTML = 0;
                }
            });
        });
        </script>



    </body>

</html>