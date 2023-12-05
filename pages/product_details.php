<?php
session_start();
include_once('../mod/database_connection.php');

$id_product = $_GET['id'];

$sql_sl_product = "SELECT *, IF(pc.product_id IS NULL, 0, ROUND( AVG(rating) ) ) AS avg_star,
IF( d.product_id IS NULL, p.price, ROUND(p.price * (1 - d.discount_amount/100))) AS price_new,
IF(d.product_id IS NULL,0,IF(CURDATE() BETWEEN d.start_date AND d.end_date, 1, 0)) AS is_discounted
FROM products p
LEFT JOIN product_comments pc ON p.id = pc.product_id 
LEFT JOIN discounts d ON p.id = d.product_id
WHERE p.id = $id_product GROUP BY p.id";
$result_product = $conn->query($sql_sl_product);
$row_p = $result_product->fetch_assoc();
?>

<!DOCTYPE html>


<html lang="en">

<?php include "../mod/head.php"; ?>

<body>
    <?php include "../mod/nav.php"; ?>
    <?php
    if (isset($_POST['review'])) {
        $review = $_POST['review'];
        $review_rate = $_POST['review_rate'];
        $user_id = $_SESSION['id_account'];
        $sql_insert_review = "INSERT INTO `product_comments`(`comment`, `comment_date`, `rating`, `product_id`, `user_id`) 
                                VALUES ('$review',NOW(),'$review_rate','$id_product','$user_id')";
        $conn->query($sql_insert_review);
        echo '<script>
        window.addEventListener("load", function() {
            notification_dialog("Success", "Add review successful!!!");
            $("html, body").animate({
                scrollTop: $("#frmAddReview").offset().top 
              }, 1000);
            });
            setTimeout(() => {
                location.href= "./product_details.php?id=' . $id_product . '";
              }, 2500);
        </script>';
    }

    ?>

    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">
                <li><a href="../Galaxy_Game_Store/home">Home</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><a href="../Galaxy_Game_Store/product_details?id=<?= $id_product ?>">
                        <?= $row_p['product_name'] ?>
                    </a></li>

                <li><span>
                        <?= $row_p['product_name'] ?>
                    </span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">
            <div class="row vertical-gap">
                <div class="col-lg-12">
                    <div class="nk-store-product">
                        <!-- START: info product -->
                        <div class="row vertical-gap">

                            <!-- slide img -->
                            <div class="col-lg-7">
                                <div class="nk-product-swipper">
                                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                                        class="swiper mySwiper2">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <video controls autoplay loop muted width="100%">
                                                    <source src="./uploads/<?= $row_p['video_trailer_url'] ?>"
                                                        type="video/mp4">
                                                </video>
                                            </div>
                                            <?php
                                            $sql_sl_imgs = "SELECT image_url FROM `product_images` WHERE product_id  = $id_product";
                                            $imgs = $conn->query($sql_sl_imgs) ?>
                                            <?php while ($row_img = $imgs->fetch_assoc()) { ?>
                                                <div class="swiper-slide">
                                                    <img src="./uploads/<?= $row_img['image_url'] ?>" />
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
                                                <img src="./uploads/<?= $row_p['image_avt_url'] ?>" />
                                                <div class="icon_play_video"><ion-icon class="icon_play"
                                                        name="play-outline"></ion-icon></div>
                                            </div>
                                            <?php
                                            $sql_sl_imgs = "SELECT image_url FROM `product_images` WHERE product_id  = $id_product";
                                            $imgs = $conn->query($sql_sl_imgs) ?>
                                            <?php while ($row_img = $imgs->fetch_assoc()) { ?>
                                                <div class="swiper-slide">
                                                    <img src="./uploads/<?= $row_img['image_url'] ?>" />
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- info -->
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-md-12" <?php
                                    if ($row_p['classify'] == 'gear') { ?> style="text-align: center; background-color: white;" <?php } ?>>
                                        <img <?= ($row_p['classify'] == 'gear') ? 'style="height: 200px;"' : '' ?>
                                            src="./uploads/<?= $row_p['image_avt_url'] ?>"
                                            alt="<?= $row_p['product_name'] ?>">
                                    </div>
                                </div>
                                <div class="nk-gap"></div>

                                <div class="price_product" style="font-size: 20px !important;">
                                    <?php if ($row_p['is_discounted'] == 1) { ?>
                                        <span class="price_discount"><i class="fas fa-tag-alt"></i>
                                            <?= $row_p['discount_amount'] ?>%
                                        </span>
                                        <span class="price_old" style="font-size: 15px !important; margin-top: 8px;">
                                            <?= $row_p['price'] ?><i class="fas fa-gem"></i>
                                        </span>
                                        <span class="price_new" style="font-size: 25px !important; margin-left: -10px;">
                                            <?= $row_p['price_new'] ?><i class="fas fa-gem"></i>
                                        </span>
                                    <?php } else { ?>
                                        <span class="price_new">
                                            <?= $row_p['price'] ?><i class="fas fa-gem"></i>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="nk-gap"></div>
                                <div class="nk-product-rating" style="font-size: 25px;">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <i class="<?= ($i <= $row_p['avg_star']) ? 'fa fa-star' : 'far fa-star' ?>"></i>
                                    <?php } ?>
                                </div>
                                <div class="nk-gap"></div>
                                <div class="input-group" style="display: flex; width: 100%; column-gap: 10px; ">
                                    <a href="javascript:add_to_cart(<?= $id_product ?><?= ($row_p['classify'] == 'gear') ? ", 'gear'" : '' ?>)"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1">Add to Cart</a>
                                    <a href="javascript:by_now(<?= $id_product ?><?= ($row_p['classify'] == 'gear') ? ", 'gear'" : '' ?>)"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1">Buy</a>
                                </div>
                                <div class="nk-gap"></div>

                                <!-- START: Meta -->
                                <div class="nk-product-meta">
                                    <div><strong>Categories</strong>:
                                        <?php
                                        $sql_sl_genres = "SELECT g.genre_name,g.id FROM genre_product gp, genres g WHERE g.id = gp.genre_id AND gp.product_id = $id_product";
                                        $genres = $conn->query($sql_sl_genres) ?>
                                        <?php while ($row_genre = $genres->fetch_assoc()) { ?>
                                            <a href="../Galaxy_Game_Store/store?id_category=<?= $row_genre['id'] ?>">
                                                <?= $row_genre['genre_name'] ?>
                                            </a>
                                        <?php } ?>

                                    </div>
                                </div>
                                <!-- END: Meta -->
                            </div>

                        </div>
                        <!-- END: info product -->

                        <div class="nk-gap-2"></div>


                        <!-- START: Tabs -->
                        <div class="nk-tabs">

                            <ul class="nav nav-tabs" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab-reviews" role="tab"
                                        data-toggle="tab">Reviews</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="#tab-description" role="tab"
                                        data-toggle="tab">Description</a>
                                </li>

                            </ul>

                            <div class="tab-content">

                                <!-- START: Tab Reviews -->
                                <div role="tabpanel" class="tab-pane fade show active" id="tab-reviews">
                                    <div class="nk-gap-2"></div>
                                    <!-- START: Reply -->
                                    <h3 class="h4">Add a Review</h3>
                                    <div class="nk-reply">
                                        <form class="nk-form" id="frmAddReview" name="frmAddReview" method="post">
                                            <div class="nk-gap-1"></div>
                                            <textarea class="form-control required" name="review" rows="5"
                                                placeholder="Your Review *" aria-required="true"></textarea>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-rating">
                                                <input type="radio" id="review-rate-5" name="review_rate" value="5">
                                                <label for="review-rate-5">
                                                    <span><i class="far fa-star"></i></span>
                                                    <span><i class="fa fa-star"></i></span>
                                                </label>

                                                <input type="radio" id="review-rate-4" name="review_rate" value="4">
                                                <label for="review-rate-4">
                                                    <span><i class="far fa-star"></i></span>
                                                    <span><i class="fa fa-star"></i></span>
                                                </label>

                                                <input type="radio" id="review-rate-3" name="review_rate" value="3">
                                                <label for="review-rate-3">
                                                    <span><i class="far fa-star"></i></span>
                                                    <span><i class="fa fa-star"></i></span>
                                                </label>

                                                <input type="radio" id="review-rate-2" name="review_rate" value="2">
                                                <label for="review-rate-2">
                                                    <span><i class="far fa-star"></i></span>
                                                    <span><i class="fa fa-star"></i></span>
                                                </label>

                                                <input type="radio" id="review-rate-1" name="review_rate" value="1">
                                                <label for="review-rate-1">
                                                    <span><i class="far fa-star"></i></span>
                                                    <span><i class="fa fa-star"></i></span>
                                                </label>
                                            </div>
                                            <button type="button" onclick="addReview()"
                                                class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Submit</button>
                                        </form>

                                        <script>
                                            function addReview() {

                                                <?php
                                                if (!isset($_SESSION['id_account'])) {
                                                    echo 'document.frmAddReview.review.value = "";
                                                         document.frmAddReview.review_rate.value = 5;
                                                        notification_dialog("Failed", "Please log in before commenting!!!");
                                                        return false;';
                                                }
                                                ?>

                                                var review = document.frmAddReview.review.value.trim();
                                                var review_rate = document.frmAddReview.review_rate.value;

                                                if (review == "" || review_rate == "") {
                                                    notification_dialog("Failed", "Enter your review and choose stars")
                                                    return false;
                                                } else {
                                                    document.frmAddReview.action = "product_details.php?id=<?= $id_product ?>";
                                                    document.frmAddReview.submit();
                                                }
                                            }
                                        </script>
                                    </div>
                                    <!-- END: Reply -->

                                    <div class="clearfix"></div>
                                    <div class="nk-gap-2"></div>
                                    <div class="nk-comments">

                                        <!-- START: Reviews -->
                                        <?php
                                        $sql_sl_comments = "SELECT pc.*,u.full_name FROM `product_comments` pc LEFT JOIN users u ON u.id = pc.user_id WHERE product_id = $id_product";
                                        $comments = $conn->query($sql_sl_comments) ?>
                                        <?php while ($row_comment = $comments->fetch_assoc()) { ?>
                                            <div class="nk-comment">
                                                <div class="nk-comment-meta">
                                                    <i class="fas fa-user"></i> by <a href="#">
                                                        <?= $row_comment['full_name'] ?>
                                                    </a> in
                                                    <a href="#">
                                                        <?= $row_comment['comment_date'] ?>
                                                    </a>
                                                    <div class="nk-review-rating">
                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                            <i
                                                                class="<?= ($i <= $row_comment['rating']) ? 'fa fa-star' : 'far fa-star' ?>"></i>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="nk-comment-text">
                                                    <p>
                                                        <?= $row_comment['comment'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!-- END: Review -->

                                    </div>
                                    <div class="btn_cmts">
                                        <button class="see-more_cmts" id="seeMore_cmts">
                                            See More
                                        </button>
                                        <button class="hidden_cmts" id="hidden_cmts" style="display: none;">
                                            Hidden
                                        </button>
                                    </div>

                                </div>
                                <!-- END: Tab Reviews -->


                                <!-- START: Tab Description -->
                                <div role="tabpanel" class="tab-pane fade " id="tab-description">
                                    <div class="nk-gap"></div>
                                    <div id="content-description">
                                        <span style="font-size: 0.8em; color: #dd163b">
                                            release date:
                                            <?= $row_p['release_date'] ?>
                                        </span>
                                        <p>
                                            <?= $row_p['description'] ?>
                                        </p>
                                    </div>

                                </div>
                                <!-- END: Tab Description -->



                            </div>
                        </div>
                        <!-- END: Tabs -->
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- START: Related Products -->
                            <div class="nk-gap-3"></div>
                            <h3 class="nk-decorated-h-2"><span><span class="text-main-1">SIMILAR </span> Products</span>
                            </h3>
                            <div class="nk-gap"></div>
                            <div class="nk-popup-gallery">
                                <div class="row vertical-gap">

                                    <?php
                                    $sql_offer = "SELECT d.product_id,d.discount_amount, p.product_name,p.image_avt_url,p.price,
                                    ROUND(p.price * (1 - d.discount_amount/100)) AS price_new, 
                                    IF(pc.product_id IS NULL, 0, ROUND( AVG(rating) ) ) AS avg_star
                                    FROM products p
                                    LEFT JOIN genre_product gp ON p.id = gp.product_id 
                                    LEFT JOIN genres g ON g.id = gp.genre_id
                                    JOIN discounts d ON p.id = d.product_id
                                    LEFT JOIN product_comments pc ON p.id = pc.product_id
                                    WHERE g.id IN (
                                      SELECT gp.genre_id 
                                      FROM genre_product gp
                                      WHERE gp.product_id = $id_product) AND CURDATE() BETWEEN d.start_date AND d.end_date
                                    AND p.id != $id_product GROUP BY p.id ORDER BY d.discount_amount DESC LIMIT 6";

                                    $result_offer = $conn->query($sql_offer);
                                    while ($row_offer = $result_offer->fetch_assoc()) { ?>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="nk-gallery-item-box">
                                                <a
                                                    href="../Galaxy_Game_Store/product_details?id=<?= $row_offer['product_id'] ?>">
                                                    <img src="./uploads/<?= $row_offer['image_avt_url'] ?>" alt="">
                                                </a>

                                                <div class="nk-gap"></div>

                                                <h2 class="nk-post-title h4">
                                                    <a
                                                        href="../Galaxy_Game_Store/product_details?id=<?= $row_offer['product_id'] ?>">
                                                        <?= $row_offer['product_name'] ?>
                                                    </a>
                                                </h2>

                                                <div class="nk-product-rating">
                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <i
                                                            class="<?= ($i <= $row_offer['avg_star']) ? 'fa fa-star' : 'far fa-star' ?>"></i>
                                                    <?php } ?>
                                                </div>

                                                <div class="mt-5">
                                                    <div>
                                                        <span class="price_discount"><i class="fas fa-tag-alt"></i>
                                                            <?= $row_offer['discount_amount'] ?>%
                                                        </span>&nbsp;
                                                        <span class="old-price">
                                                            <?= $row_offer['price'] ?><i class="fas fa-gem"></i>
                                                        </span>&nbsp;
                                                        <span class="current-price">
                                                            <?= $row_offer['price_new'] ?><i class="fas fa-gem"></i>
                                                        </span>

                                                    </div>
                                                    <div class="nk-gap"></div>
                                                    <div class="row">
                                                        <div class="col-md-7"><a
                                                                href="javascript:add_to_cart(<?= $row_offer['product_id'] ?>)"
                                                                class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ADD
                                                                TO CART</a>
                                                        </div>
                                                        <div class="col-md-5"><a
                                                                href="javascript:by_now(<?= $row_offer['product_id'] ?>)"
                                                                class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">BUY</a>
                                                        </div>
                                                    </div>

                                                    <div class="nk-gap"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>


                                </div>
                            </div>
                            <!-- END: Related Products -->


                        </div>

                        <div class="col-md-4">
                            <div class="nk-gap-3"></div>
                            <!--START: Sidebar-->
                            <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
                                <div class="nk-widget nk-widget-highlighted">
                                    <h4 class="nk-widget-title"><span><span class="text-main-1">Most</span>
                                            Popular</span></h4>

                                    <div class="nk-widget-content">
                                        <?php
                                        $sql_popular = "SELECT p.id, p.image_avt_url, p.price, p.product_name,
                                IF( d.product_id IS NULL, p.price, ROUND( p.price *(1 - d.discount_amount / 100) ) ) AS price_new,
                                IF( d.product_id IS NULL, 0, IF( CURDATE() BETWEEN d.start_date AND d.end_date, 1, 0) ) AS is_discounted,
                                IF( pc.product_id IS NULL, 0, ROUND(AVG(rating)) ) AS avg_star
                                FROM `products` p
                                LEFT JOIN product_comments pc ON p.id = pc.product_id
                                LEFT JOIN discounts d ON p.id = d.product_id
                                WHERE classify = 'gear'
                                GROUP BY p.id LIMIT 3";

                                        $result_popular = $conn->query($sql_popular);

                                        while ($row_popular = $result_popular->fetch_assoc()) { ?>
                                            <div class="nk-widget-post">
                                                <a href="../Galaxy_Game_Store/product_details?id=<?= $row_popular['id'] ?>"
                                                    class="nk-post-image">
                                                    <img src="./uploads/<?= $row_popular['image_avt_url'] ?>"
                                                        alt="<?= $row_popular['product_name'] ?>">
                                                </a>
                                                <h3 class="nk-post-title popular_name">
                                                    <a href="../Galaxy_Game_Store/product_details?id=<?= $row_popular['id'] ?>"
                                                        title="<?= $row_popular['product_name'] ?>">
                                                        <?= $row_popular['product_name'] ?>
                                                    </a>
                                                </h3>
                                                <div class="nk-product-rating mb-5">
                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <i
                                                            class="<?= ($i <= $row_popular['avg_star']) ? 'fa fa-star' : 'far fa-star' ?>"></i>
                                                    <?php } ?>
                                                </div>
                                                <div class="nk-product-price mb-5 mt-5">
                                                    <?php
                                                    if ($row_popular['is_discounted']) { ?>
                                                        <span class="old-price">
                                                            <?= $row_popular['price'] ?><i class="fas fa-gem"></i>
                                                        </span>&nbsp;
                                                        <span>
                                                            <?= $row_popular['price_new'] ?><i class="fas fa-gem"></i>
                                                        </span>
                                                    <?php } else { ?>
                                                        <?= $row_popular['price'] ?><i class="fas fa-gem"></i>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php
                                        } ?>


                                    </div>
                                </div>
                            </aside>
                            <!-- END: Sidebar -->
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

</body>

</html>

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
    seeMoreButton.addEventListener('click', function () {
        // Hiển thị tất cả các nk-comment
        for (var i = 0; i < nkComments.length; i++) {
            nkComments[i].style.display = 'block';
        }
        // Ẩn nút "See More" và hiển thị nút "Hidden"
        seeMoreButton.style.display = 'none';
        hiddenButton.style.display = 'block';
    });

    // Xử lý sự kiện click trên nút "Hidden"
    hiddenButton.addEventListener('click', function () {
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

</body>

</html>