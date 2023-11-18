<!-- START: Connect Database -->
<?php
session_start();
include_once('../mod/database_connection.php');
?>
<!-- END: Connect Database -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>GoodGames | Store</title>

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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

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
<?php
// START : Select database form (Products,Discounts,Genre_product) table
$genre_id = isset($_GET['genre_id']) ? $_GET['genre_id'] : "";
$classify = "";
// $price_min = isset($_POST['price_min']) ? $_POST['price_min'] : "";
// $price_max = isset($_POST['price_max']) ? $_POST['price_max'] : "";
$sql_product = "SELECT *, COALESCE(d.discount_amount, 0) AS discount, gp.genre_id FROM products p LEFT JOIN discounts d ON p.id = d.product_id LEFT JOIN ( SELECT product_id, GROUP_CONCAT(genre_id) AS genre_id FROM genre_product GROUP BY product_id ) gp ON p.id = gp.product_id WHERE (d.product_id IS NULL OR (d.start_date <= CURDATE() AND d.end_date >= CURDATE()))";
// đoạn này là sẽ lấy tất cả sp có trong database và kèm theo điều kiện là nếu sp nào có trong bảng discounts thì cột discount sẽ là phần trăm trong bảng discount còn sp nào không có trong bảng discounts thì cột discount sẽ là 0 , còn 1 điều kiện nữa là nếu sp nào có nhiều hơn 1 thể loại thì cột genre sẽ là chuỗi genre_id (VD: 1,2,3,..) 
$sql_genre = '';
$sql_classify = '';
$sql_price = '';
if ($genre_id != '') {
    $sql_genre = " AND gp.genre_id LIKE '%" . $genre_id . "%'";
}
if ($classify != '') {
    $sql_classify = " AND p.classify = '" . $classify . "'";
}
// if ($price_min != '' || $price_max != '') {
//     $sql_price = " AND p.price BETWEEN '" . $price_min . "' AND '" . $price_max . "'";
// }
$sql = $sql_product . $sql_genre . $sql_classify . $sql_price;
echo ($sql);
$result = mysqli_query($conn, $sql);
// END : Select database form (Products,Discounts,Genre_product) table
// =====================================================================
// START : Select database form Genres table
$genres = "SELECT * FROM `genres`";
$list_genres = mysqli_query($conn, $genres);
// END : Select database form Genres table
?>

<body>
    <?php include '../mod/navbar.php' ?>
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

            <!-- START: Image Slider -->
            <div class="nk-image-slider" data-autoplay="8000">


                <div class="nk-image-slider-item">
                    <img src="../assets/images/slide-1.jpg" alt="" class="nk-image-slider-img" data-thumb="../assets/images/slide-1-thumb.jpg">

                    <div class="nk-image-slider-content">

                        <h3 class="h4">As we Passed, I remarked</h3>
                        <p class="text-white">As we passed, I remarked a beautiful church-spire rising above some old elms in the park; and before them, in the midst of a lawn, chimneys covered with ivy, and the windows shining in the sun.</p>
                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-hover-color-main-1">Read More</a>

                    </div>

                </div>

                <div class="nk-image-slider-item">
                    <img src="../assets/images/slide-2.jpg" alt="" class="nk-image-slider-img" data-thumb="../assets/images/slide-2-thumb.jpg">

                    <div class="nk-image-slider-content">

                        <h3 class="h4">He made his passenger captain of one</h3>
                        <p class="text-white">Now the races of these two have been for some ages utterly extinct, and besides to discourse any further of them would not be at all to my purpose. But the concern I have most at heart is for our Corporation of Poets, from whom
                            I am preparing a petition to your Highness, to be subscribed with the names of one...</p>
                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-hover-color-main-1">Read More</a>

                    </div>

                </div>

                <div class="nk-image-slider-item">
                    <img src="../assets/images/slide-3.jpg" alt="" class="nk-image-slider-img" data-thumb="../assets/images/slide-3-thumb.jpg">

                </div>

                <div class="nk-image-slider-item">
                    <img src="../assets/images/slide-4.jpg" alt="" class="nk-image-slider-img" data-thumb="../assets/images/slide-4-thumb.jpg">

                    <div class="nk-image-slider-content">

                        <h3 class="h4">At length one of them called out in a clear</h3>
                        <p class="text-white">TJust then her head struck against the roof of the hall: in fact she was now more than nine feet high...</p>
                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-hover-color-main-1">Read More</a>

                    </div>

                </div>

                <div class="nk-image-slider-item">
                    <img src="../assets/images/slide-5.jpg" alt="" class="nk-image-slider-img" data-thumb="../assets/images/slide-5-thumb.jpg">

                    <div class="nk-image-slider-content">

                        <h3 class="h4">For good, too though, in consequence</h3>
                        <p class="text-white">She gave my mother such a turn, that I have always been convinced I am indebted to Miss Betsey for having been born on a Friday. The word was appropriate to the moment.</p>
                        <p class="text-white">My mother was so much worse that Peggotty, coming in with the teaboard and candles, and seeing at a glance how ill she was, - as Miss Betsey might have done sooner if there had been light enough, - conveyed her upstairs to her
                            own room with all speed; and immediately dispatched Ham Peggotty, her nephew, who had been for some days past secreted in the house...</p>
                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-hover-color-main-1">Read More</a>

                    </div>

                </div>

            </div>
            <!-- END: Image Slider -->

            <!-- START: Categories -->
            <div class="nk-gap-2"></div>
            <div class="row vertical-gap">
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="../assets/images/icon-mouse.png" alt="">
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
                            <img src="../assets/images/icon-gamepad.png" alt="">
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
                            <img src="../assets/images/icon-gamepad-2.png" alt="">
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
                <div class="col-lg-8">
                    <div class="row vertical-gap">
                        <div class="col-md-4 genre">
                            <div class="form-control select_genre" id="select_genre">
                                <span>Select Genres</span>
                                <ion-icon name="chevron-down-outline"></ion-icon>
                            </div>
                            <div class="list_genre" id="list_genre">
                                <?php
                                while ($rows = mysqli_fetch_array($list_genres)) {
                                ?>
                                    <label for="<?php echo $rows['id'] ?>" class="genre_item">
                                        <input type="checkbox" class="genre_checkbox" id="<?php echo $rows['id'] ?>" value="<?php echo $rows['id'] ?>">
                                        <label for="" class="genre_name"><?php echo $rows['genre_name'] ?></label>
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="nk-input-slider-inline">
                                <div class="nk-input-slider">
                                    <div class="nk-input-slider-content text-white">
                                        PRICE:
                                        <strong class="text-main-1">€ <span class="nk-input-slider-value-0"></span></strong>
                                        -
                                        <strong class="text-main-1">€ <span class="nk-input-slider-value-1"></span></strong>
                                    </div>
                                    <div class="nk-input-slider-input">
                                        <input type="text" name="price-filter" data-slider-min="0" data-slider-max="1800" data-slider-step="1" data-slider-value="[200, 1200]" data-slider-tooltip="hide">
                                    </div>
                                </div>
                                <div>
                                    <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white">Apply</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <form action="#" class="nk-form" novalidate="novalidate">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Type something...">
                            <button class="nk-btn nk-btn-color-main-1"><span class="ion-search"></span></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Products Filter -->

            <div class="nk-gap-2"></div>
            <div class="row vertical-gap">
                <div class="col-lg-8">
                    <!-- START: Products -->
                    <div class="row vertical-gap">
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <div class="col-md-6">
                                <div class="nk-product-cat">
                                    <a class="nk-product-image" href="store-product.html">
                                        <?php
                                        $price = $row['price'];
                                        if ($row['discount'] > 0) {
                                            $price = $row['price'] - ($row['price'] * ($row['discount'] * (1 / 100)));
                                        ?>
                                            <span class="sale"><?php echo $row['discount'] ?>%</span>
                                        <?php } ?>
                                        <img src="../uploads/<?php echo $row['image_avt_url'] ?>" alt="<?php echo $row['product_name'] ?>">
                                    </a>
                                    <div class="nk-product-cont">
                                        <h3 class="nk-product-title h5"><a href="store-product.html"><?php echo $row['product_name'] ?></a></h3>
                                        <div class="nk-gap-1"></div>
                                        <div class="nk-product-rating" data-rating="4">
                                            <?php
                                            for ($i = 1; $i <= $row['rating']; $i++) {
                                            ?>
                                                <i class="fa fa-star"></i>
                                            <?php } ?>
                                        </div>
                                        <div class="nk-gap-1"></div>
                                        <div class="nk-product-price"><?php echo $price ?> G-Coin</div>
                                        <div class="nk-gap-1"></div>
                                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Add to Cart</a>
                                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Buy</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- END: Products -->

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
                <div class="col-lg-4">
                    <!--START: Sidebar-->
                    <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">Classify</span> Product</span>
                            </h4>
                            <div class="nk-widget-content">
                                <select class="form-control">
                                    <option value="ps4">Game</option>
                                    <option value="xbox">Gear</option>
                                </select>
                            </div>
                        </div>

                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">Most</span> Popular</span>
                            </h4>
                            <div class="nk-widget-content">

                                <div class="nk-widget-post">
                                    <a href="store-product.html" class="nk-post-image">
                                        <img src="../assets/images/product-1-xs.jpg" alt="So saying he unbuckled">
                                    </a>
                                    <h3 class="nk-post-title"><a href="store-product.html">So saying he unbuckled</a></h3>
                                    <div class="nk-product-rating" data-rating="4"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="far fa-star"></i></div>
                                    <div class="nk-product-price">€ 23.00</div>
                                </div>

                                <div class="nk-widget-post">
                                    <a href="store-product.html" class="nk-post-image">
                                        <img src="../assets/images/product-2-xs.jpg" alt="However, I have reason">
                                    </a>
                                    <h3 class="nk-post-title"><a href="store-product.html">However, I have reason</a></h3>
                                    <div class="nk-product-rating" data-rating="2.5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fas fa-star-half"></i> <i class="far fa-star"></i> <i class="far fa-star"></i></div>
                                    <div class="nk-product-price">€ 32.00</div>
                                </div>

                                <div class="nk-widget-post">
                                    <a href="store-product.html" class="nk-post-image">
                                        <img src="../assets/images/product-3-xs.jpg" alt="It was some time before">
                                    </a>
                                    <h3 class="nk-post-title"><a href="store-product.html">It was some time before</a></h3>
                                    <div class="nk-product-rating" data-rating="5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                                    <div class="nk-product-price">€ 14.00</div>
                                </div>

                            </div>
                        </div>

                    </aside>
                    <!-- END: Sidebar -->
                </div>
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
    <script src="../assets/js/navbar.js"></script>
    <!-- END: Scripts -->



</body>
<script>
    const genre_select = document.getElementById('select_genre');
    const genres = document.getElementById('list_genre');

    genre_select.onclick = (event) => {
        event.stopPropagation();
        genres.style.display = 'flex';
    }

    document.onclick = (event) => {
        const isClickedInsideGenres = genres.contains(event.target);
        if (!isClickedInsideGenres) {
            genres.style.display = 'none';
        }
    };
</script>

<script>
    var inputElements = document.querySelectorAll('.genre_checkbox');
    var genre_item = document.querySelectorAll('.genre_item');
    for (var i = 0; i < inputElements.length; i++) {
        if (inputElements[i].checked) {
            var inputValue = inputElements[i].value;
            console.log(inputValue);
        }
    }
</script>
<script>
    // const price_min = document.getElementById('price_min');
    // const price_max = document.getElementById('price_max');
    // $.post('./store.php', {
    //     page: 'filter',
    //     price_min: price_min.innerText,
    //     price_max: price_max.innerText,
    // }, function(data) {
    //     $('body').html(data);
    // })
</script>

</html>