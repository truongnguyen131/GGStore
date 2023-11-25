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

    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">
                <li><a href="../Galaxy_Game_Store/home">Home</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><a href="../Galaxy_Game_Store/product_details?id=<?= $row_p['id'] ?>">
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
                        <div class="row vertical-gap">
                            <div class="col-lg-7">
                                <!-- START: Product Photos -->
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
                                <!-- END: Product Photos -->
                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="./uploads/<?= $row_p['image_avt_url'] ?>"
                                            alt="<?= $row_p['product_name'] ?>">
                                    </div>
                                </div>
                                <div class="nk-gap-2"></div>

                                <div class="price_product">
                                    <?php if ($row_p['is_discounted'] == 1) { ?>
                                        <div class="price_product__sale">
                                            <?= $row_p['discount_amount'] ?>%
                                        </div>
                                        <div class="price_old">
                                            <?= $row_p['price'] ?><i class="fas fa-gem"></i>
                                        </div>
                                        <div class="price_new">
                                            <?= $row_p['price_new'] ?><i class="fas fa-gem"></i>
                                        </div>
                                    <?php } else { ?>
                                        <div class="price_new">
                                            <?= $row_p['price'] ?><i class="fas fa-gem"></i>
                                        </div>
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
                                    <a href="javascript:add_to_cart(<?= $row_p['id'] ?>)"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1">Add to Cart</a>
                                    <a href="javascript:by_now(<?= $row_p['id'] ?>)"
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
                                            <a
                                                href="../Galaxy_Game_Store/store?id_category=<?= password_hash($row_genre['id'], PASSWORD_DEFAULT) ?>">
                                                <?= $row_genre['genre_name'] ?>
                                            </a>
                                        <?php } ?>

                                    </div>
                                </div>
                                <!-- END: Meta -->
                            </div>
                        </div>
                        <div class="nk-gap-2"></div>
                        <!-- START: Tabs -->
                        <div class="nk-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab-description" role="tab"
                                        data-toggle="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab-reviews" role="tab" data-toggle="tab">Reviews</a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                <!-- START: Tab Description -->
                                <div role="tabpanel" class="tab-pane fade show active" id="tab-description">
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

                                <!-- START: Tab Reviews -->
                                <div role="tabpanel" class="tab-pane fade" id="tab-reviews">
                                    <div class="nk-gap-2"></div>
                                    <!-- START: Reply -->
                                    <h3 class="h4">Add a Review</h3>
                                    <div class="nk-reply">
                                        <form class="nk-form" name="frmAddReview">
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
                                        <div id="add_review_return"></div>
                                        <script>
                                            function addReview() {
                                                var review = document.frmAddReview.review.value.trim();
                                                var review_rate = document.frmAddReview.review_rate.value;

                                                if (review == "" || review_rate == "") {
                                                    notification_dialog("Failed", "Enter your review and choose stars")
                                                    return false;
                                                } else {
                                                    $.post('../Galaxy_Game_Store/pages/add_review.php', { review: review, review_rate: review_rate }, function (data) {
                                                        $('#add_review_return').html(data);
                                                    });
                                                }
                                            }
                                        </script>
                                    </div>
                                    <!-- END: Reply -->

                                    <div class="clearfix"></div>
                                    <div class="nk-gap-2"></div>
                                    <div class="nk-comments">
                                        <!-- START: Review -->
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
                            <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Related</span> Products</span>
                            </h3>
                            <div class="nk-gap"></div>
                            <div class="row vertical-gap">
                                <div class="col-md-6">
                                    <div class="nk-product-cat">
                                        <a class="nk-product-image" href="store-product.html">
                                            <img src="assets/images/product-11-xs.jpg" alt="She gave my mother">
                                        </a>
                                        <div class="nk-product-cont">
                                            <h3 class="nk-product-title h5"><a href="store-product.html">She gave my
                                                    mother</a>
                                            </h3>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-rating" data-rating="3"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="far fa-star"></i> <i class="far fa-star"></i>
                                            </div>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-price">€ 14.00</div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="nk-product-cat">
                                        <a class="nk-product-image" href="store-product.html">
                                            <img src="assets/images/product-12-xs.jpg" alt="A hundred thousand">
                                        </a>
                                        <div class="nk-product-cont">
                                            <h3 class="nk-product-title h5"><a href="store-product.html">A hundred
                                                    thousand</a>
                                            </h3>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-rating" data-rating="4.5"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="fas fa-star-half"></i>
                                            </div>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-price">€ 20.00</div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="nk-product-cat">
                                        <a class="nk-product-image" href="store-product.html">
                                            <img src="assets/images/product-13-xs.jpg" alt="So saying he unbuckled">
                                        </a>
                                        <div class="nk-product-cont">
                                            <h3 class="nk-product-title h5"><a href="store-product.html">So saying he
                                                    unbuckled</a></h3>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-rating" data-rating="5"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="fa fa-star"></i>
                                            </div>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-price">€ 23.00</div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="nk-product-cat">
                                        <a class="nk-product-image" href="store-product.html">
                                            <img src="assets/images/product-14-xs.jpg" alt="However, I have reason">
                                        </a>
                                        <div class="nk-product-cont">
                                            <h3 class="nk-product-title h5"><a href="store-product.html">However, I have
                                                    reason</a></h3>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-rating" data-rating="1.5"> <i class="fa fa-star"></i>
                                                <i class="fas fa-star-half"></i> <i class="far fa-star"></i> <i
                                                    class="far fa-star"></i> <i class="far fa-star"></i>
                                            </div>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-price">€ 32.00</div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="nk-product-cat">
                                        <a class="nk-product-image" href="store-product.html">
                                            <img src="assets/images/product-15-xs.jpg" alt="At first, for some time">
                                        </a>
                                        <div class="nk-product-cont">
                                            <h3 class="nk-product-title h5"><a href="store-product.html">At first, for
                                                    some
                                                    time</a></h3>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-rating" data-rating="4"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="far fa-star"></i>
                                            </div>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-price">€ 14.00</div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="nk-product-cat">
                                        <a class="nk-product-image" href="store-product.html">
                                            <img src="assets/images/product-16-xs.jpg"
                                                alt="When the last &#39;natural&#39;">
                                        </a>
                                        <div class="nk-product-cont">
                                            <h3 class="nk-product-title h5"><a href="store-product.html">When the last
                                                    &#39;natural&#39;</a></h3>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-rating" data-rating="4.5"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="fas fa-star-half"></i>
                                            </div>
                                            <div class="nk-gap-1"></div>
                                            <div class="nk-product-price">€ 20.00</div>
                                        </div>
                                    </div>
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
                                            Popular</span>
                                    </h4>
                                    <div class="nk-widget-content">

                                        <div class="nk-widget-post">
                                            <a href="store-product.html" class="nk-post-image">
                                                <img src="assets/images/product-1-xs.jpg" alt="So saying he unbuckled">
                                            </a>
                                            <h3 class="nk-post-title"><a href="store-product.html">So saying he
                                                    unbuckled</a>
                                            </h3>
                                            <div class="nk-product-rating" data-rating="4"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="far fa-star"></i>
                                            </div>
                                            <div class="nk-product-price">€ 23.00</div>
                                        </div>

                                        <div class="nk-widget-post">
                                            <a href="store-product.html" class="nk-post-image">
                                                <img src="assets/images/product-2-xs.jpg" alt="However, I have reason">
                                            </a>
                                            <h3 class="nk-post-title"><a href="store-product.html">However, I have
                                                    reason</a>
                                            </h3>
                                            <div class="nk-product-rating" data-rating="2.5"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fas fa-star-half"></i> <i
                                                    class="far fa-star"></i> <i class="far fa-star"></i>
                                            </div>
                                            <div class="nk-product-price">€ 32.00</div>
                                        </div>

                                        <div class="nk-widget-post">
                                            <a href="store-product.html" class="nk-post-image">
                                                <img src="assets/images/product-3-xs.jpg" alt="It was some time before">
                                            </a>
                                            <h3 class="nk-post-title"><a href="store-product.html">It was some time
                                                    before</a>
                                            </h3>
                                            <div class="nk-product-rating" data-rating="5"> <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="fa fa-star"></i>
                                            </div>
                                            <div class="nk-product-price">€ 14.00</div>
                                        </div>
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