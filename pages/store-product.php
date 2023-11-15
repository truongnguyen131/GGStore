<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>GoodGames</title>

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

    <!-- swipper -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>



    <!-- ionicon -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<!-- START: Connect Database -->
<?php
session_start();
include_once('../mod/database_connection.php');
?>
<!-- END: Connect Database -->
<!-- ================================ -->
<?php
$user_id = isset($_GET['us_id']) ? $_GET['us_id'] : "33";
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : "1";
// ============================================
//START: Select database form table products
$sql_Product = "SELECT * FROM products AS p
        WHERE p.id = ?";
$stmt = $conn->prepare($sql_Product);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result();
while ($row = mysqli_fetch_array($product)) {
    $name_product = $row['product_name'];
    $imgAvt_product = $row['image_avt_url'];
    $classify_product = $row['classify'];
    $description_product = $row['description'];
    $videoTrailer_product = $row['video_trailer_url'];
    $source_product = $row['source'];
    $price_product = $row['price'];
    $rating_product = $row['rating'];
    $releaseDate_product = $row['release_date'];
}
//END: Select database form table products
// ==================================
//START: Select database form table product_images
$sql_ImageProduct = "SELECT * FROM `product_images` WHERE `product_id` = ?";
$stmt = $conn->prepare($sql_ImageProduct);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$ImgProduct = $stmt->get_result();
$list_ImgProduct = array();
while ($row_ImgProduct = mysqli_fetch_array($ImgProduct)) {
    $list_ImgProduct[] = $row_ImgProduct['image_url'];
}
//END: Select database form table product_images
// =======================================
//START: Select database form table product_comments
$sql_CmtProduct = "SELECT * FROM `products_comments` as pc , `users` as u WHERE pc.user_id = u.id and pc.product_id = ?";
$stmt = $conn->prepare($sql_CmtProduct);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$CmtProduct = $stmt->get_result();
// count amout cmt and avg star 
$sql_countProductID = "SELECT COUNT(product_id), AVG(user_rating) FROM `products_comments` as pc WHERE pc.product_id = ?";
$stmt = $conn->prepare($sql_countProductID);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$NumberProduct = $stmt->get_result();
while ($row = mysqli_fetch_array($NumberProduct)) {
    $count_numberCmt = $row['COUNT(product_id)'];
    $avg_star = ceil($row['AVG(user_rating)']);
}
//END: Select database form table product_comments
// ==================================================
//START: Select database form table genres and genre_product
$sql_genreProduct = "SELECT * FROM `genres` as g,`genre_product` as gp WHERE g.id = gp.genre_id and gp.product_id = ?";
$stmt = $conn->prepare($sql_genreProduct);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$genre_Product = $stmt->get_result();
//END: Select database form table genres and genre_product
?>

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
                <li><a href="store-catalog.html"><?php echo $classify_product ?></a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><span><?php echo $name_product ?></span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">
            <div class="row vertical-gap">
                <div class="col-lg-8">
                    <div class="nk-store-product">
                        <div class="row vertical-gap">
                            <div class="col-md-12">
                                <!-- START: Product Photos -->
                                <div class="nk-product-swipper">
                                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <img src="../uploads/<?php echo $imgAvt_product ?>" />
                                            </div>
                                            <?php
                                            foreach ($list_ImgProduct as $url_ImgProduct) {
                                            ?>
                                                <div class="swiper-slide">
                                                    <img src="../uploads/<?php echo $url_ImgProduct ?>" />
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="swiper-button-next">
                                            <ion-icon name="chevron-forward-outline" class="arrow"></ion-icon>
                                        </div>
                                        <div class="swiper-button-prev">
                                            <ion-icon name="chevron-back-outline" class="arrow"></ion-icon>
                                        </div>
                                    </div>
                                    <div thumbsSlider="" class="swiper mySwiper">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <img src="../uploads/<?php echo $imgAvt_product ?>" />
                                            </div>
                                            <?php
                                            foreach ($list_ImgProduct as $url_ImgProduct) {
                                            ?>
                                                <div class="swiper-slide">
                                                    <img src="../uploads/<?php echo $url_ImgProduct ?>" />
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Product Photos -->
                            </div>
                            <div class="col-md-12">
                                <!-- START: Add to Cart -->
                                <form action="#" class="nk-product-addtocart">
                                    <div class="nk-product-price"><?php echo $price_product ?> G-Coin</div>
                                    <div class="nk-gap-1"></div>
                                    <div class="input-group">
                                        <input type="number" class="form-control" value="1" min="1" max="21">
                                        <button class="nk-btn nk-btn-rounded nk-btn-color-main-1">Add to Cart</button>
                                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-main-1">Buy</a>
                                    </div>
                                </form>
                                <div class="nk-gap-3"></div>
                                <!-- END: Add to Cart -->

                                <!-- START: Meta -->
                                <div class="nk-product-meta">
                                    <div><strong>Categories</strong>:
                                        <?php
                                        $rowCount = mysqli_num_rows($genre_Product);
                                        $counter = 0;
                                        while ($row = mysqli_fetch_array($genre_Product)) {
                                            $counter++;
                                        ?>
                                            <a href="#"><?php echo $row['genre_name'] ?></a>
                                        <?php
                                            if ($counter < $rowCount) {
                                                echo ',';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <!-- <div><strong>Tags</strong>: <a href="#">blizzard</a>, <a href="#">action</a>, <a href="#">MMO</a></div> -->
                                </div>
                                <!-- END: Meta -->
                            </div>
                        </div>
                        <!-- START: Trailer -->
                        <div class="nk-product-trailer">
                            <video src="../uploads/<?php echo $videoTrailer_product ?>" autoplay loop muted></video>
                        </div>
                        <!-- END: Trailer -->
                        <!-- START: Share -->
                        <div class="nk-gap-2"></div>
                        <div class="nk-post-share">
                            <span class="h5">Share Product:</span>
                            <ul class="nk-social-links-2">
                                <li><span class="nk-social-facebook" title="Share page on Facebook" data-share="facebook"><span class="fab fa-facebook"></span></span>
                                </li>
                                <li><span class="nk-social-google-plus" title="Share page on Google+" data-share="google-plus"><span class="fab fa-google-plus"></span></span>
                                </li>
                                <li><span class="nk-social-twitter" title="Share page on Twitter" data-share="twitter"><span class="fab fa-twitter"></span></span>
                                </li>
                                <li><span class="nk-social-pinterest" title="Share page on Pinterest" data-share="pinterest"><span class="fab fa-pinterest-p"></span></span>
                                </li>
                            </ul>
                        </div>
                        <!-- END: Share -->

                        <div class="nk-gap-2"></div>
                        <!-- START: Tabs -->
                        <div class="nk-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab-description" role="tab" data-toggle="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab-reviews" role="tab" data-toggle="tab">Reviews (
                                        <?php
                                        echo $count_numberCmt;
                                        ?>
                                        )</a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                <!-- START: Tab Description -->
                                <div role="tabpanel" class="tab-pane fade show active" id="tab-description">
                                    <div class="nk-gap"></div>
                                    <strong class="text-white">Release Date: <?php echo $releaseDate_product ?></strong>
                                    <div class="nk-gap"></div>
                                    <div id="content-description">
                                        <p><?php echo $description_product ?></p>
                                    </div>
                                    <span id="see-more" onclick="showMore()">See More</span>
                                    <span id="hidden" onclick="showHidden()">Hidden</span>

                                    <div class="nk-product-info-row row vertical-gap">
                                        <div class="col-md-5">
                                            <div class="nk-product-pegi">
                                                <div class="nk-gap"></div>
                                                <img src="../assets/images/pegi-icon.jpg" alt="">
                                                <div class="nk-product-pegi-cont">
                                                    <strong class="text-white">Pegi Rating:</strong>
                                                    <div class="nk-gap"></div>
                                                    Suitable for people aged 12 and over.
                                                </div>
                                                <div class="nk-gap"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="nk-gap"></div>
                                            <strong class="text-white">Genre:</strong>
                                            <div class="nk-gap"></div>
                                            TBD
                                            <div class="nk-gap"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="nk-gap"></div>
                                            <strong class="text-white">Customer Rating:</strong>
                                            <div class="nk-gap"></div>
                                            <div class="nk-product-rating" data-rating="<?php echo $avg_star ?>">
                                                <?php for ($i = 1; $i <= $avg_star; $i++) { ?>
                                                    <i class="fa fa-star"></i>
                                                <?php } ?>
                                            </div>
                                            <div class="nk-gap"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Tab Description -->

                                <!-- START: Tab Reviews -->
                                <div role="tabpanel" class="tab-pane fade" id="tab-reviews">
                                    <div class="nk-gap-2"></div>
                                    <!-- START: Reply -->
                                    <h3 class="h4">Add a Review</h3>
                                    <div class="nk-reply">
                                        <div class="nk-gap-1"></div>
                                        <textarea class="form-control required" id="comment" rows="5" placeholder="Your Review *" aria-required="true"></textarea>
                                        <span id="errocmt"></span>
                                        <div class="nk-gap-1"></div>
                                        <div class="nk-rating">
                                            <input type="radio" id="review-rate-5" name="review-rate" value="5">
                                            <label for="review-rate-5">
                                                <span><i class="far fa-star"></i></span>
                                                <span><i class="fa fa-star"></i></span>
                                            </label>

                                            <input type="radio" id="review-rate-4" name="review-rate" value="4">
                                            <label for="review-rate-4">
                                                <span><i class="far fa-star"></i></span>
                                                <span><i class="fa fa-star"></i></span>
                                            </label>

                                            <input type="radio" id="review-rate-3" name="review-rate" value="3">
                                            <label for="review-rate-3">
                                                <span><i class="far fa-star"></i></span>
                                                <span><i class="fa fa-star"></i></span>
                                            </label>

                                            <input type="radio" id="review-rate-2" name="review-rate" value="2">
                                            <label for="review-rate-2">
                                                <span><i class="far fa-star"></i></span>
                                                <span><i class="fa fa-star"></i></span>
                                            </label>

                                            <input type="radio" id="review-rate-1" name="review-rate" value="1">
                                            <label for="review-rate-1">
                                                <span><i class="far fa-star"></i></span>
                                                <span><i class="fa fa-star"></i></span>
                                            </label>
                                        </div>
                                        <button class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right" id="btn_comment" onclick="check_comment()">Comment</button>
                                    </div>
                                    <!-- END: Reply -->

                                    <div class="clearfix"></div>
                                    <div class="nk-gap-2"></div>
                                    <div class="nk-comments">
                                        <?php
                                        mysqli_data_seek($CmtProduct, 0);
                                        while ($row = mysqli_fetch_array($CmtProduct)) {
                                            // Tạo đối tượng DateTime từ biến $date_save
                                            $date_cmt = DateTime::createFromFormat("Y-m-d H:i:s", $row['comment_date']);
                                            // Tạo đối tượng DateTime từ thời gian hiện tại
                                            $date_now = new DateTime();
                                            // Tính toán khoảng cách thời gian giữa hai đối tượng DateTime
                                            $interval = $date_cmt->diff($date_now);
                                            $display_date = "";
                                            // Xác định khoảng cách thời gian và hiển thị kết quả phù hợp
                                            if ($interval->y > 0) {
                                                $display_date = $interval->format("%y year ago");
                                            } else if ($interval->m > 0) {
                                                $display_date = $interval->format("%m month ago");
                                            } else if ($interval->d > 0) {
                                                $display_date = $interval->format("%d day ago");
                                            } elseif ($interval->h > 0) {
                                                $display_date = $interval->format("%h hour ago");
                                            } elseif ($interval->i > 0) {
                                                $display_date = $interval->format("%i minute ago");
                                            } else {
                                                $display_date = "just now";
                                            }
                                        ?>
                                            <!-- START: Review -->
                                            <div class="nk-comment">
                                                <div class="nk-comment-meta">
                                                    <img src="../assets/images/avatar-2.jpg" alt="Witch Murder" class="rounded-circle" width="35"> by <a href="#"><?php echo $row['full_name'] ?></a> in <?php echo $display_date ?>
                                                    <div class="nk-review-rating" data-rating="<?php echo $row['user_rating'] ?>">
                                                        <?php
                                                        for ($i = 1; $i <= $row['user_rating']; $i++) { ?>
                                                            <i class="fa fa-star"></i>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="nk-comment-text">
                                                    <p><?php echo $row['comment'] ?></p>
                                                </div>
                                            </div>
                                            <!-- END: Review -->
                                        <?php } ?>
                                    </div>
                                    <div class="btn_cmts">
                                        <?php
                                        if ($count_numberCmt < 3) {
                                        ?>
                                            <button class="see-more_cmts" id="seeMore_cmts" style="display: none;">See More</button>
                                        <?php } else { ?>
                                            <button class="see-more_cmts" id="seeMore_cmts">See More</button>
                                        <?php } ?>
                                        <button class="hidden_cmts" id="hidden_cmts" style="display: none;">
                                            Hidden
                                        </button>
                                    </div>

                                </div>
                                <!-- END: Tab Reviews -->

                            </div>
                        </div>
                        <!-- END: Tabs -->
                    </div>

                    <!-- START: Related Products -->
                    <div class="nk-gap-3"></div>
                    <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Related</span> Products</span>
                    </h3>
                    <div class="nk-gap"></div>
                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <div class="nk-product-cat">
                                <a class="nk-product-image" href="store-product.html">
                                    <img src="../assets/images/product-11-xs.jpg" alt="She gave my mother">
                                </a>
                                <div class="nk-product-cont">
                                    <h3 class="nk-product-title h5"><a href="store-product.html">She gave my mother</a>
                                    </h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-rating" data-rating="3"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i></div>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 14.00</div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="nk-product-cat">
                                <a class="nk-product-image" href="store-product.html">
                                    <img src="../assets/images/product-12-xs.jpg" alt="A hundred thousand">
                                </a>
                                <div class="nk-product-cont">
                                    <h3 class="nk-product-title h5"><a href="store-product.html">A hundred thousand</a>
                                    </h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-rating" data-rating="4.5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fas fa-star-half"></i></div>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 20.00</div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="nk-product-cat">
                                <a class="nk-product-image" href="store-product.html">
                                    <img src="../assets/images/product-13-xs.jpg" alt="So saying he unbuckled">
                                </a>
                                <div class="nk-product-cont">
                                    <h3 class="nk-product-title h5"><a href="store-product.html">So saying he
                                            unbuckled</a></h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-rating" data-rating="5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 23.00</div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="nk-product-cat">
                                <a class="nk-product-image" href="store-product.html">
                                    <img src="../assets/images/product-14-xs.jpg" alt="However, I have reason">
                                </a>
                                <div class="nk-product-cont">
                                    <h3 class="nk-product-title h5"><a href="store-product.html">However, I have
                                            reason</a></h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-rating" data-rating="1.5"> <i class="fa fa-star"></i> <i class="fas fa-star-half"></i> <i class="far fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i></div>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 32.00</div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="nk-product-cat">
                                <a class="nk-product-image" href="store-product.html">
                                    <img src="../assets/images/product-15-xs.jpg" alt="At first, for some time">
                                </a>
                                <div class="nk-product-cont">
                                    <h3 class="nk-product-title h5"><a href="store-product.html">At first, for some
                                            time</a></h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-rating" data-rating="4"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="far fa-star"></i></div>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 14.00</div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="nk-product-cat">
                                <a class="nk-product-image" href="store-product.html">
                                    <img src="../assets/images/product-16-xs.jpg" alt="When the last &#39;natural&#39;">
                                </a>
                                <div class="nk-product-cont">
                                    <h3 class="nk-product-title h5"><a href="store-product.html">When the last
                                            &#39;natural&#39;</a></h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-rating" data-rating="4.5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fas fa-star-half"></i></div>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 20.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Related Products -->
                </div>
                <div class="col-lg-4">
                    <!--
                START: Sidebar

                Additional Classes:
                    .nk-sidebar-left
                    .nk-sidebar-right
                    .nk-sidebar-sticky
            -->
                    <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
                        <div class="nk-widget">
                            <div class="nk-widget-content">
                                <form action="#" class="nk-form nk-form-style-1" novalidate="novalidate">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Type something...">
                                        <button class="nk-btn nk-btn-color-main-1"><span class="ion-search"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">Category</span> Menu</span>
                            </h4>
                            <div class="nk-widget-content">
                                <select class="nk-widget-categories">
                                    <?php 
                                     $sql_genres =  mysqli_query($conn, "SELECT * FROM `genres`") or die();
                                     while($row = mysqli_fetch_array( $sql_genres)){
                                    ?>
                                    <option><a href="#"><?php echo $row['genre_name']?></a></option>
                                  <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">Price</span> Filter</span>
                            </h4>
                            <div class="nk-widget-content">
                                <div class="nk-input-slider">
                                    <input type="text" name="price-filter" data-slider-min="0" data-slider-max="1800" data-slider-step="1" data-slider-value="[200, 1200]" data-slider-tooltip="hide">
                                    <div class="nk-gap"></div>
                                    <div>
                                        <div class="text-white mt-4 float-left">
                                            PRICE:
                                            <strong class="text-main-1">€ <span class="nk-input-slider-value-0"></span></strong> -
                                            <strong class="text-main-1">€ <span class="nk-input-slider-value-1"></span></strong>
                                        </div>
                                        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white float-right">Apply</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">We</span> Are Social</span>
                            </h4>
                            <div class="nk-widget-content">
                                <ul class="nk-social-links-3 nk-social-links-cols-4">
                                    <li><a class="nk-social-twitch" href="#"><span class="fab fa-twitch"></span></a>
                                    </li>
                                    <li><a class="nk-social-instagram" href="#"><span class="fab fa-instagram"></span></a></li>
                                    <li><a class="nk-social-facebook" href="#"><span class="fab fa-facebook"></span></a>
                                    </li>
                                    <li><a class="nk-social-google-plus" href="#"><span class="fab fa-google-plus"></span></a></li>
                                    <li><a class="nk-social-youtube" href="#"><span class="fab fa-youtube"></span></a>
                                    </li>
                                    <li><a class="nk-social-twitter" href="#" target="_blank"><span class="fab fa-twitter"></span></a></li>
                                    <li><a class="nk-social-pinterest" href="#"><span class="fab fa-pinterest-p"></span></a></li>
                                    <li><a class="nk-social-rss" href="#"><span class="fa fa-rss"></span></a></li>
                                </ul>
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
                                    <h3 class="nk-post-title"><a href="store-product.html">So saying he unbuckled</a>
                                    </h3>
                                    <div class="nk-product-rating" data-rating="4"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="far fa-star"></i></div>
                                    <div class="nk-product-price">€ 23.00</div>
                                </div>

                                <div class="nk-widget-post">
                                    <a href="store-product.html" class="nk-post-image">
                                        <img src="../assets/images/product-2-xs.jpg" alt="However, I have reason">
                                    </a>
                                    <h3 class="nk-post-title"><a href="store-product.html">However, I have reason</a>
                                    </h3>
                                    <div class="nk-product-rating" data-rating="2.5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fas fa-star-half"></i> <i class="far fa-star"></i> <i class="far fa-star"></i></div>
                                    <div class="nk-product-price">€ 32.00</div>
                                </div>

                                <div class="nk-widget-post">
                                    <a href="store-product.html" class="nk-post-image">
                                        <img src="../assets/images/product-3-xs.jpg" alt="It was some time before">
                                    </a>
                                    <h3 class="nk-post-title"><a href="store-product.html">It was some time before</a>
                                    </h3>
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
    <script src="../../assets/js/navbar.js"></script>
    <!-- END: Scripts -->



</body>

</html>
<!-- nK Share -->
<script src="../assets/plugins/nk-share/nk-share.js"></script>

<!-- GoodGames -->
<script src="../assets/js/goodgames.min.js"></script>
<script src="../assets/js/goodgames-init.js"></script>
<!-- END: Scripts -->


<script>
    function showMore() {
        var contentContainer = document.getElementById('content-description');
        var seeMoreButton = document.getElementById('see-more');
        var hiddenButton = document.getElementById('hidden');

        contentContainer.style.maxHeight = 'none'; // Hiển thị toàn bộ văn bản
        seeMoreButton.style.display = 'none'; // Ẩn nút 'See More'
        hiddenButton.style.display = 'inline'; // Hiển thị nút 'Hidden'
    }

    function showHidden() {
        var contentContainer = document.getElementById('content-description');
        var hiddenButton = document.getElementById('hidden');
        var seeMoreButton = document.getElementById('see-more');

        contentContainer.style.maxHeight = '10em'; // Giới hạn chiều cao của văn bản là khoảng 10 dòng
        seeMoreButton.style.display = 'inline'; // Hiển thị nút 'See More'
        hiddenButton.style.display = 'none'; // Ẩn nút 'Hidden'
    }
</script>
<script>
    var contentContainer = document.getElementById('content-description');
    var seeMoreButton = document.getElementById('see-more');
    var hiddenButton = document.getElementById('hidden');

    // Kiểm tra số dòng văn bản
    var lineCount = contentContainer.clientHeight / parseInt(window.getComputedStyle(contentContainer).lineHeight);
    if (lineCount > 10) {
        contentContainer.style.maxHeight = '10em'; // Giới hạn chiều cao của văn bản là khoảng 10 dòng
        seeMoreButton.style.display = 'inline'; // Hiển thị nút 'See More'
    } else {
        hiddenButton.style.display = 'none'; // Ẩn nút 'Hidden' nếu không có dòng văn bản bị tràn
    }
</script>

<script>
    var nkComments = document.querySelectorAll('.nk-comments .nk-comment');
    var seeMoreButton = document.getElementById('seeMore_cmts');
    var hiddenButton = document.getElementById('hidden_cmts');

    // Số lượng nk-comment hiển thị ban đầu (2)
    var initialVisibleComments = 2;

    // Ẩn tất cả các nk-comment vượt quá số lượng hiển thị ban đầu
    for (var i = initialVisibleComments; i < nkComments.length; i++) {
        nkComments[i].style.display = 'none';
    }

    // Xử lý sự kiện click trên nút "See More"
    seeMoreButton.addEventListener('click', function() {
        // Hiển thị tất cả các nk-comment
        for (var i = 0; i < nkComments.length; i++) {
            nkComments[i].style.display = 'block';
        }
        // Ẩn nút "See More" và hiển thị nút "Hidden"
        seeMoreButton.style.display = 'none';
        hiddenButton.style.display = 'block';
    });

    // Xử lý sự kiện click trên nút "Hidden"
    hiddenButton.addEventListener('click', function() {
        // Ẩn các nk-comment vượt quá số lượng hiển thị ban đầu
        for (var i = initialVisibleComments; i < nkComments.length; i++) {
            nkComments[i].style.display = 'none';
        }
        // Hiển thị nút "See More" và ẩn nút "Hidden"
        seeMoreButton.style.display = 'inline-block';
        hiddenButton.style.display = 'none';
    });
</script>



<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".mySwiper2", {
        loop: true,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
    });
</script>


<script>
    function check_comment() {
        // if (user_id == "") {
        //         var results = confirm("Vui lòng đăng nhập để lưu tin!!");
        //         if (results) {
        //             var url = './login.php';
        //             window.location.href = url;
        //         }
        //     }
        var user_id = <?php echo $user_id ?>;
        var product_id = <?php echo $product_id ?>;
        var check_cmt = false;
        var input_cmt = $('#comment').val();
        if (input_cmt.length == 0 || input_cmt.trim().length === 0) {
            $('#errocmt').html("Please enter a comment!!");
        } else {
            $('#errocmt').html("");
            check_cmt = true;
        }
        var star = document.getElementsByName('review-rate');
        for (var i = 0; i < star.length; i++) {
            if (star[i].checked) {
                var number_star = star[i].value;
            }
        }
        if (check_cmt == true) {
            $.post('./processing_sql.php', {
                page: 'comment',
                us_id: user_id,
                product_id: product_id,
                input_cmt: input_cmt,
                star: number_star
            }, function(data) {
                $('body').html(data);
            })
        }
    }
</script>
</body>

</html>