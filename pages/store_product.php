<?php
session_start();
include_once('./mod/database_connection.php');
?>



<!DOCTYPE html>
<html lang="en">
<!-- <link rel="stylesheet" href="./assets/css/grand_custom.css"> -->
<link rel="stylesheet" href="./assets/css/goodgames2.css">
<?php include "./mod/head.php"; ?>

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
$sql_CmtProduct = "SELECT * FROM `product_comments` as pc , `users` as u WHERE pc.user_id = u.id and pc.product_id = ?";
$stmt = $conn->prepare($sql_CmtProduct);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$CmtProduct = $stmt->get_result();
// count amout cmt and avg star 
$sql_countProductID = "SELECT COUNT(product_id), AVG(rating) FROM `product_comments` as pc WHERE pc.product_id = ?";
$stmt = $conn->prepare($sql_countProductID);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$NumberProduct = $stmt->get_result();
while ($row = mysqli_fetch_array($NumberProduct)) {
    $count_numberCmt = $row['COUNT(product_id)'];
    $avg_star = ceil($row['AVG(rating)']);
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
    <?php include './mod/nav.php' ?>
    <!-- END: Navbar -->
    <div class="nk-main">

        <!-- START: Breadcrumbs -->
        <li><span class="fa fa-angle-right"></span></li>
        <li><a href="store.html">Store</a></li>
        <li><span class="fa fa-angle-right"></span></li>
        <li><a href="store-catalog.html">Action Games</a></li>
        <li><a href="store-catalog.html"><?php echo $classify_product ?></a></li>
        <li><span class="fa fa-angle-right"></span></li>
        <li><span>Call of Duty</span></li>

        <li><span><?php echo $name_product ?></span></li>
        </ul>
    </div>
    <div class="nk-gap-1"></div>
    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-1.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-2.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-3.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-4.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-5.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-6.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-7.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-8.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-9.jpg" />
            </div>
            <div class="swiper-slide">
                <img src="https://swiperjs.com/demos/images/nature-10.jpg" />
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
            <div thumbsSlider="" class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-1.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-2.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-3.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-4.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-5.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-6.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-7.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-8.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-9.jpg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="https://swiperjs.com/demos/images/nature-10.jpg" />
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
        <div class="col-md-12">
            <!-- START: Add to Cart -->
            <form action="#" class="nk-product-addtocart">
                <div class="nk-product-price">€ 32.00</div>
                <div class="nk-product-price"><?php echo $price_product ?> G-Coin</div>
                <div class="nk-gap-1"></div>
                <div class="input-group">
                    <input type="number" class="form-control" value="1" min="1" max="21">

                    <!-- START: Meta -->
                    <div class="nk-product-meta">
                        <div><strong>SKU</strong>: 300-200-503</div>
                        <div><strong>Categories</strong>: <a href="#">online</a>, <a href="#">FPS</a>, <a href="#">MMO</a>, <a href="#">Action games</a></div>
                        <div><strong>Tags</strong>: <a href="#">blizzard</a>, <a href="#">action</a>, <a href="#">MMO</a></div>
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
            <video src="../assets/mp3/trailer.mp3" autoplay loop muted></video>
            <video src="../uploads/<?php echo $videoTrailer_product ?>" autoplay loop muted></video>
        </div>
        <!-- END: Trailer -->
        <!-- START: Share -->
        <a class="nav-link active" href="#tab-description" role="tab" data-toggle="tab">Description</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tab-reviews" role="tab" data-toggle="tab">Reviews (3)</a>
            <a class="nav-link" href="#tab-reviews" role="tab" data-toggle="tab">Reviews (
                <?php
                echo $count_numberCmt;
                ?>
                )</a>
        </li>
        </ul>

        <!-- START: Tab Description -->
        <div role="tabpanel" class="tab-pane fade show active" id="tab-description">
            <div class="nk-gap"></div>
            <strong class="text-white">Release Date: 24/05/2018</strong>
            <strong class="text-white">Release Date: <?php echo $releaseDate_product ?></strong>
            <div class="nk-gap"></div>
            <div id="content-description">
                <p>She gave my mother such a turn, that I have always been convinced I am indebted to Miss Betsey for having been born on a Friday. The word was appropriate to the moment. My mother was so much worse that Peggotty,
                    coming in with the teaboard and candles, and seeing at a glance how ill she was, - as Miss Betsey might have done sooner if there had been light enough, - conveyed her upstairs to her own room with all speed;
                    coming in with the teaboard and candles, and seeing at a glance how ill she was, - as Miss Betsey might have done sooner if there had been light enough, - conveyed her upstairs to her own room with all speed;
                    coming in with the teaboard and candles, and seeing at a glance how ill she was, - as Miss Betsey might have done sooner if there had been light enough, - conveyed her upstairs to her own room with all speed;
                    coming in with the teaboard and candles, and seeing at a glance how ill she was, - as Miss Betsey might have done sooner if there had been light enough, - conveyed her upstairs to her own room with all speed;
                    and immediately dispatched Ham Peggotty, her nephew, who had been for some days past secreted in the house, unknown to my mother, as a special messenger in case of emergency, to fetch the nurse and doctor.</p>
                <p><?php echo $description_product ?></p>
            </div>
            <span id="see-more" onclick="showMore()">See More</span>
            <span id="hidden" onclick="showHidden()">Hidden</span>
            <div class="nk-gap"></div>
            <strong class="text-white">Customer Rating:</strong>
            <div class="nk-gap"></div>
            <div class="nk-product-rating" data-rating="4.5"> <i class="fa fa-star"></i>
                <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fas fa-star-half"></i>
                <div class="nk-product-rating" data-rating="<?php echo $avg_star ?>">
                    <?php for ($i = 1; $i <= $avg_star; $i++) { ?>
                        <i class="fa fa-star"></i>
                    <?php } ?>
                </div>
                <div class="nk-gap"></div>
            </div>
            <!-- START: Reply -->
            <h3 class="h4">Add a Review</h3>
            <div class="nk-reply">
                <form action="#" class="nk-form">
                    <div class="nk-gap-1"></div>
                    <textarea class="form-control required" name="message" rows="5" placeholder="Your Review *" aria-required="true"></textarea>
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
                    <button class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Submit</button>
                </form>
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
                <!-- START: Review -->
                <div class="nk-comment">
                    <div class="nk-comment-meta">
                        <img src="../assets/images/avatar-2.jpg" alt="Witch Murder" class="rounded-circle" width="35"> by <a href="#">Witch Murder</a> in 20 September, 2018
                        <div class="nk-review-rating" data-rating="4.5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="far fa-star"></i></div>
                    </div>
                    <div class="nk-comment-text">
                        <p>Upon replenish great rule. Were tree, given day him night Fruit it moveth all. First they're creature seasons and creature fill a it have fifth, their own subdue brought above divided.</p>

                        <p>Behold it set, seas seas and meat divided Moveth cattle forth evening above moveth so, signs god a fruitful his after called that whose.
                        </p>
                    </div>
                </div>
                <!-- END: Review -->
                <!-- START: Review -->
                <div class="nk-comment">
                    <div class="nk-comment-meta">
                        <img src="../assets/images/avatar-1.jpg" alt="Hitman" class="rounded-circle" width="35"> by <a href="#">Hitman</a> in 14 Jule, 2018
                        <div class="nk-review-rating" data-rating="0.5"> <i class="fa fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i></div>
                    </div>
                    <div class="nk-comment-text">
                        <p> I was awakened at daybreak by the charwoman, and having arrived at the inn, was at first placed inside the coach.</p>
                    </div>
                </div>
                <!-- END: Review -->
                <!-- START: Review -->
                <div class="nk-comment">
                    <div class="nk-comment-meta">
                        <img src="../assets/images/avatar-3.jpg" alt="Wolfenstein" class="rounded-circle" width="35"> by <a href="#">Wolfenstein</a> in 27 June, 2018
                        <div class="nk-review-rating" data-rating="3.5"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                    </div>
                    <div class="nk-comment-text">
                        <p>Divided thing, land it evening earth winged whose great after. Were grass night. To Air itself saw bring fly fowl. Fly years behold spirit day greater of wherein winged and form. Seed open don't thing midst
                            created dry every greater divided of, be man is. Second Bring stars fourth gathering he hath face morning fill. Living so second darkness. Moveth were male. May creepeth. Be tree fourth.</p>
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
                                    <div class="nk-review-rating" data-rating="<?php echo $row['rating'] ?>">
                                        <?php
                                        for ($i = 1; $i <= $row['rating']; $i++) { ?>
                                            <i class="fa fa-star"></i>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="nk-comment-text">
                                    <p><?php echo $row['comment'] ?></p>
                                </div>
                            </div>
                    </div>
                    <!-- END: Review -->
                    <!-- END: Review -->
                <?php } ?>
                </div>
                <div class="btn_cmts">
                    <button class="see-more_cmts" id="seeMore_cmts">
                        See More
                    </button>
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
                    </h3>
                    <div class="nk-gap"></div>
                    <div class="row vertical-gap">


                        <div class="col-md-6">
                            <div class="nk-product-cat">
                                <a class="nk-product-image" href="store-product.html">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- END: Related Products -->
            </div>
            <form action="#" class="nk-form nk-form-style-1" novalidate="novalidate">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Type something...">
                    <button class="nk-btn nk-btn-color-main-1"><span class="ion-search"></span></button>
                    <button class="nk-btn nk-btn-color-main-1"><span class="ion-search"></span></button>
                </div>
            </form>
        </div>
        </h4>
        <div class="nk-widget-content">
            <select class="nk-widget-categories">
                <?php
                $sql_genres =  mysqli_query($conn, "SELECT * FROM `genres`") or die();
                while ($row = mysqli_fetch_array($sql_genres)) {
                ?>
                    <option><a href="#"><?php echo $row['genre_name'] ?></a></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div>
        <div class="text-white mt-4 float-left">
            PRICE:
            <strong class="text-main-1">€ <span class="nk-input-slider-value-0"></span></strong> -
            <strong class="text-main-1">€ <span class="nk-input-slider-value-1"></span></strong>
            <strong class="text-main-1">€ <span class="nk-input-slider-value-0"></span></strong> -
            <strong class="text-main-1">€ <span class="nk-input-slider-value-1"></span></strong>
        </div>
        <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white float-right">Apply</a>
    </div>
    <!-- START: Footer -->

    <!-- END: Footer -->


    <!-- START: Scripts -->
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