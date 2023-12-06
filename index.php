<?php
session_start();
include_once('./mod/database_connection.php');
?>



<!DOCTYPE html>
<html lang="en">

<?php include "./mod/head.php"; ?>


<body>

    <?php include "./mod/nav.php"; ?>


    <div class="nk-main">

        <div class="nk-gap"></div>

        <div class="container">

            <!-- START: Image Slider -->
            <div class="nk-image-slider" data-autoplay="5000">

                <div class="nk-image-slider-item">
                    <img src="assets/images/slide4.jpg" alt="" class="nk-image-slider-img"
                        data-thumb="assets/images/slide4-thumb.jpg">
                </div>

                <div class="nk-image-slider-item">
                    <img src="assets/images/slide3.jpg" alt="" class="nk-image-slider-img"
                        data-thumb="assets/images/slide3-thumb.jpg">
                </div>

                <div class="nk-image-slider-item">
                    <img src="assets/images/slide1.jpg" alt="" class="nk-image-slider-img"
                        data-thumb="assets/images/slide1-thumb.jpg">
                </div>

                <div class="nk-image-slider-item">
                    <img src="assets/images/slide2.jpg" alt="" class="nk-image-slider-img"
                        data-thumb="assets/images/slide2-thumb.jpg">
                </div>

            </div>
            <!-- END: Image Slider -->

            <!-- START: PC PS4 XBOX -->
            <div class="nk-gap-2"></div>

            <div class="row vertical-gap">
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="assets/images/icon-mouse1.png" width="65px" alt="">
                        </div>
                        <div class="nk-feature-cont">
                            <h3 class="nk-feature-title"><a href="#">GAME PC</a></h3>
                            <h4 class="nk-feature-title text-main-1"><a href="#">View Games</a></h4>
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
                            <h4 class="nk-feature-title text-main-1"><a href="#">View Games</a></h4>
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
                            <h4 class="nk-feature-title text-main-1"><a href="#">View Games</a></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: PC PS4 XBOX -->

            <div class="nk-gap-3"></div>

            <!-- START: RECOMMENDED -->
            <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Recommend</span> games</span></h3>
            <div class="recommend-slider">


                <?php
                $game_recently_launched = true;

                if (isset($_SESSION['id_account'])) {
                    $user_id = $_SESSION["id_account"];
                    $sql_top_3_genre = 'SELECT g.genre_name, COUNT(*) AS total FROM order_details od
                        INNER JOIN orders o ON o.id = od.order_id
                        INNER JOIN genre_product gp ON gp.product_id = od.product_id
                        INNER JOIN products p ON gp.product_id = p.id                                    
                        INNER JOIN genres g ON g.id = gp.genre_id 
                        WHERE o.customer_id = ? AND p.classify = "game"
                        GROUP BY g.genre_name ORDER BY total DESC LIMIT 3';

                    $stmt_top_3_genre = $conn->prepare($sql_top_3_genre);
                    $stmt_top_3_genre->bind_param("i", $user_id);
                    $stmt_top_3_genre->execute();
                    $result_top_3_genre = $stmt_top_3_genre->get_result();
                    $stmt_top_3_genre->close();

                    $genres = '';

                    if ($result_top_3_genre->num_rows > 0) {

                        while ($row = $result_top_3_genre->fetch_assoc()) {
                            $genres .= "'" . $row['genre_name'] . "'" . ',';
                        }
                        $genres = trim($genres, ',');

                        $sql_recommended = "SELECT p.*, d.discount_amount,
                        GROUP_CONCAT(pi.image_url ORDER BY pi.product_id SEPARATOR ',' LIMIT 4) AS imgs_detail,
                        round(p.price - (p.price * d.discount_amount/100)) AS new_price FROM products p
                        JOIN genre_product gp ON gp.product_id = p.id
                        JOIN genres g ON g.id = gp.genre_id  
                        JOIN discounts d ON d.product_id = p.id
                        JOIN product_images pi ON pi.product_id = p.id
                        WHERE g.genre_name IN ($genres) 
                        AND p.id NOT IN (SELECT product_id FROM purchased_products
                        WHERE customer_id = ? AND status = 'not trading')
                        AND NOW() BETWEEN d.start_date AND d.end_date
                        GROUP BY p.id ORDER BY d.discount_amount DESC LIMIT 6;";

                        $stmt_recommended = $conn->prepare($sql_recommended);
                        $stmt_recommended->bind_param("i", $user_id);
                        $stmt_recommended->execute();
                        $result_recommended = $stmt_recommended->get_result();
                        $stmt_recommended->close();

                        if ($result_recommended->num_rows > 0) {
                            $game_recently_launched = false;
                        }
                    }
                }

                if ($game_recently_launched) {
                    $sql_game_recently_launched = "SELECT p.*, d.discount_amount,round(p.price - (p.price * d.discount_amount/100)) AS new_price,
                    GROUP_CONCAT(pi.image_url ORDER BY pi.product_id SEPARATOR ',' LIMIT 4) AS imgs_detail
                    FROM products p JOIN discounts d ON d.product_id = p.id JOIN product_images pi ON pi.product_id = p.id
                    WHERE release_date <= NOW() AND classify = 'game' AND NOW() BETWEEN d.start_date AND d.end_date
                    GROUP BY p.id ORDER BY release_date DESC LIMIT 6";
                    $result_recommended = $conn->query($sql_game_recently_launched);
                }

                while ($row_recommended = $result_recommended->fetch_assoc()) {
                    ?>
                    <div class="recommend-item">
                        <div class="slideshow">
                            <a href="../Galaxy_Game_Store/product_details?id=<?= $row_recommended['id'] ?>">
                                <img src="./uploads/<?= $row_recommended['image_avt_url'] ?>"
                                    alt="<?= $row_recommended['product_name'] ?>"
                                    id="slideshow-img-<?= $row_recommended['id'] ?>">
                            </a>
                        </div>
                        <div class="tab-slideshow">
                            <div class="tab-slideshow__name-product">
                                <a href="../Galaxy_Game_Store/product_details?id=<?= $row_recommended['id'] ?>">
                                    <h3>
                                        <?= $row_recommended['product_name'] ?>
                                    </h3>
                                </a>
                            </div>
                            <div class="tab-slideshow__img">
                                <?php
                                $imgs_detail = explode(",", $row_recommended['imgs_detail']);
                                foreach ($imgs_detail as $value) { ?>
                                    <div class="tab-slideshow__img-item">
                                        <img src="uploads/<?= $value ?>" alt="" class="tab-img"
                                            onmouseover="getImageSrc(<?= $row_recommended['id'] ?>,'<?= $value ?>')"
                                            onmouseout="resetImageSrc(<?= $row_recommended['id'] ?>,'<?= $row_recommended['image_avt_url'] ?>')">
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="tab-slideshow__control">
                                <div class="price">
                                    <div class="discount">
                                        <?= $row_recommended['discount_amount'] ?>%
                                    </div>
                                    <div class="price_old">
                                        <?= $row_recommended['price'] ?> <i class="fas fa-gem"></i>
                                    </div>
                                    <div class="price_new">
                                        <?= $row_recommended['new_price'] ?> <i class="fas fa-gem"></i>
                                    </div>
                                </div>
                                <div class="btn_control">
                                    <a href="javascript:add_to_cart(<?= $row_recommended['id'] ?>)"><button class="btn">Add
                                            to Cart</button></a>
                                    <a href="javascript:by_now(<?= $row_recommended['id'] ?>)" class="btn">Buy</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <script>
                    function getImageSrc(id_main_img, url_img) {
                        var id_main_img = "slideshow-img-" + id_main_img;
                        const main_img = document.getElementById(id_main_img).src;
                        document.getElementById(id_main_img).src = "uploads/" + url_img;
                    }

                    function resetImageSrc(id_main_img, url_main_img) {
                        var id_main_img = "slideshow-img-" + id_main_img;
                        document.getElementById(id_main_img).src = "uploads/" + url_main_img;
                    }
                </script>

            </div>
            <!-- END: RECOMMENDED -->

            <div class="nk-gap-3"></div>

            <!-- START: Upcoming games -->
            <h3 id="UPCOMING" class="nk-decorated-h-2"><span><span class="text-main-1">Upcoming</span> games</span></h3>

            <div class="nk-gap"></div>

            <div class="nk-news-box">
                <div class="nk-news-box-list">
                    <div class="nano">
                        <div class="nano-content" id="nano-content-1">
                            <?php
                            $sql = "SELECT * FROM products WHERE classify = 'game' AND release_date > NOW() ORDER BY release_date";
                            $stmt = $conn->prepare($sql);

                            if ($stmt === false) {
                                die("Error preparing statement");
                            }

                            if (!$stmt->execute()) {
                                die("Execute failed: " . $stmt->error);
                            }
                            $result = $stmt->get_result();
                            $stmt->close();
                            ?>
                            <?php $i = 1;
                            while ($row = $result->fetch_assoc()) {
                                if ($i == 1) {
                                    $video = $row["video_trailer_url"];
                                    $description = $row["description"];
                                    $release_date = $row["release_date"];
                                    $product_name = $row["product_name"];
                                } ?>
                                <div class="nk-news-box-item <?= ($i == 1) ? "nk-news-box-item-active" : "" ?>">
                                    <div class="nk-news-box-item-img">
                                        <img src="./uploads/<?= $row['image_avt_url'] ?>" alt="<?= $row['product_name'] ?>">
                                    </div>
                                    <video autoplay muted width="100%" hidden>
                                        <source src="./uploads/<?= $row["video_trailer_url"] ?>" type="video/mp4">
                                    </video>
                                    <h3 class="nk-news-box-item-title">
                                        <?= $row['product_name'] ?>
                                    </h3>
                                    <div class="nk-news-box-item-text">
                                        <p>
                                            <?= $row['description'] ?>
                                        </p>
                                    </div>
                                    <div class="nk-news-box-item-date">
                                        <span class="fa fa-calendar"></span>
                                        <?= date('M d, Y', strtotime($row['release_date'])) ?>
                                    </div>
                                </div>
                                <?php $i++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="nk-news-box-each-info">
                    <div class="nano">
                        <div class="nano-content">
                            <!-- There will be inserted info about selected news-->
                            <div class="nk-news-box-item-video">
                                <video autoplay muted width="100%">
                                    <source src="./uploads/<?= $video ?>" type="video/mp4">
                                </video>
                            </div>
                            <h3 class="nk-news-box-item-title">
                                <?= $product_name ?>
                            </h3>
                            <div class="nk-news-box-item-text">
                                <p>
                                    <?= $description ?>
                                </p>
                            </div>

                            <div class="nk-news-box-item-date">
                                <span class="fa fa-calendar"></span>
                                <?= date('M d, Y', strtotime($release_date)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const nanoContent = document.getElementById('nano-content-1');
                nanoContent.addEventListener('click', handleClick);

                // Kiểm tra xem có phần tử được thêm class hay không
                async function handleClick(event) {
                    await new Promise(resolve => setTimeout(resolve, 50));

                    // Lấy video element từ nk-news-box-item-active
                    const activeVideo = document.querySelector('.nk-news-box-item-active video source');

                    // Lấy source từ video trong nk-news-box-item-active
                    const source_replace = activeVideo.src;

                    // Lưu container video
                    const videoContainer = document.querySelector('.nk-news-box-item-video');

                    // Tạo video mới
                    const newVideo = document.createElement('video');

                    // Set lại các thuộc tính
                    newVideo.autoplay = true;
                    newVideo.muted = true;
                    newVideo.style.width = '100%';

                    // Thêm source mới
                    const source_new = document.createElement('source');
                    source_new.src = source_replace;
                    source_new.type = 'video/mp4';

                    // Thêm source vào video
                    newVideo.appendChild(source_new);

                    // Xóa nội dung cũ 
                    videoContainer.innerHTML = '';

                    // Thêm video mới vào container
                    videoContainer.appendChild(newVideo);
                }
            </script>
            <!-- END: Upcoming games -->

            <div class="nk-gap-3"></div>

            <!-- START: TOP 10 BEST SELLERS -->

            <h3 id="TOP_10" class="nk-decorated-h-2"><span><span class="text-main-1">TOP 10</span> BEST SELLERS</span>
            </h3>

            <div class="nk-gap"></div>

            <div class="nk-carousel nk-carousel-x2" data-autoplay="2500" data-dots="false" data-cell-align="left"
                data-arrows="true">
                <div class="nk-carousel-inner">

                    <?php
                    $sql_top10 = "SELECT *, GROUP_CONCAT(g.genre_name) AS genres,
                    IF(
                      d.product_id IS NULL,
                      p.price, 
                      ROUND(p.price * (1 - d.discount_amount/100))
                    ) AS price_new,
                    IF(
                      d.product_id IS NULL,
                      0,
                      IF(CURDATE() BETWEEN d.start_date AND d.end_date, 1, 0) 
                    ) AS is_discounted
                  FROM 
                    (SELECT * FROM products ORDER BY units_sold DESC LIMIT 10) p
                  
                  LEFT JOIN genre_product gp ON p.id = gp.product_id
                  LEFT JOIN genres g ON gp.genre_id = g.id 
                  
                  LEFT JOIN discounts d ON p.id = d.product_id
                  GROUP BY p.id ORDER BY p.units_sold DESC";

                    $result_top10 = $conn->query($sql_top10);
                    while ($row_top10 = $result_top10->fetch_assoc()) { ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="nk-blog-post" style="margin-left: 20px;">
                                <a href="../Galaxy_Game_Store/product_details?id=<?= $row_top10['product_id'] ?>"
                                    class="nk-post-img" data-img="<?= $row_top10['id'] ?>"
                                    data-video="<?= $row_top10['id'] ?>">
                                    <img id="img_<?= $row_top10['id'] ?>" src="./uploads/<?= $row_top10['image_avt_url'] ?>"
                                        alt="<?= $row_top10['product_name'] ?>">
                                    <video id="video_<?= $row_top10['id'] ?>" autoplay muted width="100%" hidden>
                                        <source src="./uploads/<?= $row_top10['video_trailer_url'] ?>" type="video/mp4">
                                    </video>
                                </a>
                                <div class="nk-gap"></div>
                                <h2 class="nk-post-title h4">
                                    <a href="../Galaxy_Game_Store/product_details?id=<?= $row_top10['product_id'] ?>">
                                        <?= $row_top10['product_name'] ?>
                                    </a>
                                </h2>
                                <div class="nk-post-by">
                                    <span style="color: white;">Release date:</span>&nbsp;
                                    <?= date('M d, Y', strtotime($row_top10['release_date'])) ?>
                                </div>
                                <div class="nk-post-text">
                                    <p>
                                        <?= substr($row_top10['description'], 0, 70) . (strlen($row_top10['description']) > 50 ? '...' : '') ?>
                                    </p>
                                </div>

                                <div class="nk-post-text" style="margin-top: 8px; margin-bottom: 20px;">
                                    <span style="color: white;font-size: 1.1em;">Price:</span>&nbsp;
                                    <?php
                                    if ($row_top10['is_discounted'] == 1) { ?>
                                        <span class="price_discount"><i class="fas fa-tag-alt"></i>
                                            <?= $row_top10['discount_amount'] ?>%
                                        </span>&nbsp;
                                        <span class="old-price">
                                            <?= $row_top10['price'] ?><i class="fas fa-gem"></i>
                                        </span>&nbsp;
                                        <span class="current-price">
                                            <?= $row_top10['price_new'] ?><i class="fas fa-gem"></i>
                                        </span>
                                    <?php } else { ?>
                                        <span class="price">
                                            <?= $row_top10['price'] ?><i class="fas fa-gem"></i>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="nk-post-text" style="font-size: 0.8em;">
                                    <p> <span style="color: white;">Genre-tags:</span>
                                        <?php
                                        $genres = explode(",", $row_top10['genres']);
                                        foreach ($genres as $genre) {
                                            ?>
                                            <a href="#">
                                                <?php echo $genre; ?>
                                            </a>
                                        <?php } ?>
                                    </p>
                                </div>
                                <div class="nk-gap"></div>

                                <a href="javascript:add_to_cart(<?= $row_top10['product_id'] ?>)"
                                    class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ADD
                                    TO CART</a>
                                <a href="javascript:by_now(<?= $row_top10['product_id'] ?>)"
                                    class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">BY
                                    NOW</a>
                            </div>
                        </div>
                    <?php } ?>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const postImgs = document.querySelectorAll('.nk-post-img');

                            postImgs.forEach(img => {

                                img.addEventListener('mouseenter', e => {
                                    handleMouseEnter(e.target.dataset.img, e.target.dataset.video);
                                });

                                img.addEventListener('mouseleave', e => {
                                    handleMouseLeave(e.target.dataset.img, e.target.dataset.video);
                                });

                            });
                        })

                        function handleMouseEnter(imgId, videoId) {
                            var img_id = 'img_' + imgId;
                            var video_id = 'video_' + videoId;
                            const img = document.getElementById(img_id);
                            const video = document.getElementById(video_id);
                            img.hidden = true;
                            video.hidden = false;
                        }

                        function handleMouseLeave(imgId, videoId) {
                            var img_id = 'img_' + imgId;
                            var video_id = 'video_' + videoId;
                            const img = document.getElementById(img_id);
                            const video = document.getElementById(video_id);
                            img.hidden = false;
                            video.hidden = true;
                        }
                    </script>

                </div>
            </div>

            <!-- END: TOP 10 BEST SELLERS -->

            <!-- START: SPECIAL OFFERS, GEAR PC, TABBED NEWS, RECOMMENDED, POPULAR -->
            <div class="nk-gap-2"></div>

            <div class="row vertical-gap">

                <!-- START: Sidebar1 -->
                <div class="col-lg-8">

                    <!-- START: SPECIAL OFFERS -->
                    <h3 id="SPECIAL_OFFERS" class="nk-decorated-h-2"><span><span class="text-main-1">SPECIAL</span>
                            OFFERS</span></h3>
                    <div class="nk-gap"></div>
                    <div class="nk-popup-gallery">
                        <div class="row vertical-gap">

                            <?php
                            $sql_offer = "SELECT d.product_id,d.discount_amount, p.product_name,p.image_avt_url,p.price,
                            ROUND(p.price * (1 - d.discount_amount/100)) AS price_new, 
                            IF(pc.product_id IS NULL, 0, ROUND( AVG(rating) ) ) AS avg_star
                            FROM products p LEFT JOIN discounts d ON p.id = d.product_id
                            LEFT JOIN product_comments pc ON p.id = pc.product_id
                            WHERE CURDATE() BETWEEN d.start_date AND d.end_date GROUP BY p.id
                            ORDER BY d.discount_amount DESC LIMIT 6";

                            $result_offer = $conn->query($sql_offer);
                            while ($row_offer = $result_offer->fetch_assoc()) { ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="nk-gallery-item-box">
                                        <a href="../Galaxy_Game_Store/product_details?id=<?= $row_offer['product_id'] ?>">
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

                            <div class="col-lg-4 col-md-4"></div>
                            <div class="col-lg-4 col-md-4 d-flex align-items-center justify-content-center">
                                <a href="../Galaxy_Game_Store/store?classify=game" style="margin-top: -20px;"
                                    class="nk-btn nk-btn-rounded nk-btn-color-main-1 w-100">See More</a>
                            </div>
                            <div class="col-lg-4 col-md-4"></div>
                        </div>
                    </div>
                    <!-- END: SPECIAL OFFERS -->

                    <!-- START: Gear -->
                    <div class="nk-gap-3"></div>
                    <h3 id="GEAR_PC" class="nk-decorated-h-2"><span><span class="text-main-1">Gear</span> PC</span></h3>
                    <div class="nk-gap"></div>
                    <div class="row vertical-gap">
                        <?php
                        $sql_gear = "SELECT p.id, p.image_avt_url, p.price, p.product_name,
                        IF( d.product_id IS NULL, p.price, ROUND( p.price *(1 - d.discount_amount / 100) ) ) AS price_new,
                        IF( d.product_id IS NULL, 0, IF( CURDATE() BETWEEN d.start_date AND d.end_date, 1, 0) ) AS is_discounted,
                        IF( pc.product_id IS NULL, 0, ROUND(AVG(rating)) ) AS avg_star
                        FROM `products` p
                        LEFT JOIN product_comments pc ON p.id = pc.product_id
                        LEFT JOIN discounts d ON p.id = d.product_id
                        WHERE classify = 'gear'
                        GROUP BY p.id LIMIT 4";

                        $result_gear = $conn->query($sql_gear);
                        while ($row_gear = $result_gear->fetch_assoc()) { ?>
                            <div class="col-md-6">
                                <div class="nk-product-cat">
                                    <a class="nk-product-image"
                                        href="../Galaxy_Game_Store/product_details?id=<?= $row_gear['id'] ?>">
                                        <img src="./uploads/<?= $row_gear['image_avt_url'] ?>"
                                            alt="<?= $row_gear['product_name'] ?>">
                                    </a>
                                    <div class="nk-product-cont">
                                        <h3 class="nk-product-title h5 mb-10"><a
                                                href="../Galaxy_Game_Store/product_details?id=<?= $row_gear['id'] ?>">
                                                <?= $row_gear['product_name'] ?>
                                            </a>
                                        </h3>
                                        <div class="nk-product-rating mb-5">
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <i
                                                    class="<?= ($i <= $row_gear['avg_star']) ? 'fa fa-star' : 'far fa-star' ?>"></i>
                                            <?php } ?>
                                        </div>
                                        <div class="nk-product-price mb-5 mt-5">
                                            <?php
                                            if ($row_gear['is_discounted']) { ?>
                                                <span class="old-price">
                                                    <?= $row_gear['price'] ?><i class="fas fa-gem"></i>
                                                </span>&nbsp;
                                                <span>
                                                    <?= $row_gear['price_new'] ?><i class="fas fa-gem"></i>
                                                </span>
                                            <?php } else { ?>
                                                <?= $row_gear['price'] ?><i class="fas fa-gem"></i>
                                            <?php } ?>
                                        </div>
                                        <a href="javascript:add_to_cart(<?= $row_gear['id'] ?>,'gear')"
                                            class="mt-7 nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Add
                                            to Cart</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <a href="../Galaxy_Game_Store/store?classify=gear"
                                class="nk-btn nk-btn-rounded nk-btn-color-main-1 w-100">See More</a>
                        </div>
                        <div class="col-md-4"></div>

                    </div>

                    <!-- END: Gear -->

                    <!-- START: Tabbed News  -->
                    <div class="nk-gap-3"></div>
                    <h3 id="TABBED_NEWS" class="nk-decorated-h-2"><span><span class="text-main-1">Tabbed</span>
                            News</span></h3>
                    <div class="nk-gap"></div>
                    <div class="nk-tabs">
                        <ul class="nav nav-tabs nav-tabs-fill" role="tablist">

                            <?php
                            $sql_news_type = "SELECT * FROM news_type";
                            $i = 1;
                            $result_news_type = $conn->query($sql_news_type);
                            while ($row_news_type = $result_news_type->fetch_assoc()) { ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($i == 1) ? "active" : "" ?>"
                                        href="#tabs-1-<?= $row_news_type['id'] ?>" role="tab" data-toggle="tab">
                                        <?= $row_news_type['news_type_name'] ?>
                                    </a>
                                </li>
                                <?php $i++;
                            } ?>
                        </ul>
                        <div class="tab-content">
                            <?php
                            $sql_news_type = "SELECT * FROM news_type";
                            $i = 1;
                            $result_news_type = $conn->query($sql_news_type);
                            while ($row_news_type = $result_news_type->fetch_assoc()) { ?>
                                <div role="tabpanel" class="tab-pane fade show <?= ($i == 1) ? "active" : "" ?>"
                                    id="tabs-1-<?= $row_news_type['id'] ?>">
                                    <div class="nk-gap"></div>

                                    <?php
                                    $news_type_id = $row_news_type['id'];
                                    $sql_news = "SELECT *, 
                                    (SELECT COUNT(*) FROM news_comments nc WHERE nc.news_id = n.id) AS comment_count,
                                    (SELECT image FROM news_content WHERE news_id = n.id AND image != '' LIMIT 1) AS image
                                    FROM news n WHERE news_type_id = $news_type_id ORDER BY `n`.`publish_date` ASC LIMIT 3";
                                    $result_news = $conn->query($sql_news);
                                    $j = 1;
                                    while ($row_news = $result_news->fetch_assoc()) {
                                        if ($j == 1) { ?>
                                            <div class="nk-blog-post nk-blog-post-border-bottom">
                                                <?php if ($row_news['image'] != "") { ?>
                                                    <a href="../Galaxy_Game_Store/news?id=<?= $row_news['id'] ?>" class="nk-post-img">
                                                        <img style='height: 200px;' src="./uploads/<?= $row_news['image'] ?>"
                                                            alt="<?= $row_news['title'] ?>">
                                                    </a>
                                                <?php } ?>

                                                <div class="nk-gap-1"></div>
                                                <h2 class="nk-post-title h4"><a
                                                        href="../Galaxy_Game_Store/news?id=<?= $row_news['id'] ?>">
                                                        <?= $row_news['title'] ?>
                                                    </a></h2>
                                                <div class="nk-post-date mt-10 mb-10">
                                                    <span class="fa fa-calendar"></span>
                                                    <?= date('M d, Y', strtotime($row_news['publish_date'])) ?>
                                                    <span class="fa fa-comments"></span> <a href="#">
                                                        <?= $row_news['comment_count'] ?> comments
                                                    </a>
                                                </div>
                                                <div class="nk-post-text">
                                                    <p>
                                                        <?= substr($row_news['header'], 0, 170) . (strlen($row_news['header']) > 170 ? '...' : '') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="nk-blog-post nk-blog-post-border-bottom">
                                                <div class="row vertical-gap">
                                                    <div class="col-lg-3 col-md-5">
                                                        <?php if ($row_news['image'] != "") { ?>
                                                            <a href="../Galaxy_Game_Store/news?id=<?= $row_news['id'] ?>"
                                                                class="nk-post-img">
                                                                <img src="./uploads/<?= $row_news['image'] ?>"
                                                                    alt="<?= $row_news['title'] ?>">
                                                            </a>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="col-lg-9 col-md-7">
                                                        <h2 class="nk-post-title h4 news_title"><a
                                                                href="../Galaxy_Game_Store/news?id=<?= $row_news['id'] ?>"
                                                                title="<?= $row_news['title'] ?>">
                                                                <?= $row_news['title'] ?>
                                                            </a></h2>
                                                        <div class="nk-post-date mt-10 mb-10">
                                                            <span class="fa fa-calendar"></span>
                                                            <?= date('M d, Y', strtotime($row_news['publish_date'])) ?>
                                                            <span class="fa fa-comments"></span> <a href="#">
                                                                <?= $row_news['comment_count'] ?> comments
                                                            </a>
                                                        </div>
                                                        <div class="nk-post-text">
                                                            <p>
                                                                <?= substr($row_news['header'], 0, 60) . (strlen($row_news['header']) > 60 ? '...' : '') ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php $j++;
                                    } ?>

                                    <div class="nk-gap"></div>
                                </div>
                                <?php $i++;
                            } ?>

                        </div>
                    </div>
                    <!-- END: Tabbed News -->

                </div>
                <!-- END: Sidebar1 -->


                <!-- START: Sidebar2 -->
                <div class="col-lg-4">
                    <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">

                        <!-- we are social -->
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">We</span> Are Social</span></h4>
                            <div class="nk-widget-content">
                                <ul class="nk-social-links-3 nk-social-links-cols-4">
                                    <li><a class="nk-social-twitch" href="#"><span class="fab fa-twitch"></span></a>
                                    </li>
                                    <li><a class="nk-social-instagram" href="#"><span
                                                class="fab fa-instagram"></span></a></li>
                                    <li><a class="nk-social-facebook" href="#"><span class="fab fa-facebook"></span></a>
                                    </li>
                                    <li><a class="nk-social-google-plus" href="#"><span
                                                class="fab fa-google-plus"></span></a></li>
                                    <li><a class="nk-social-youtube" href="#"><span class="fab fa-youtube"></span></a>
                                    </li>
                                    <li><a class="nk-social-twitter" href="#" target="_blank"><span
                                                class="fab fa-twitter"></span></a></li>
                                    <li><a class="nk-social-pinterest" href="#"><span
                                                class="fab fa-pinterest-p"></span></a></li>
                                    <li><a class="nk-social-rss" href="#"><span class="fa fa-rss"></span></a></li>
                                </ul>
                            </div>
                        </div>

                        <?php
                        if (isset($_SESSION['id_account'])) { ?>
                            <!--START: MINI GAME -->
                            <div class="nk-widget nk-widget-highlighted">
                                <h4 class="nk-widget-title"><span><span class="text-main-1">Mini </span>Game</span>
                                </h4>
                                <?php include_once('./pages/mini_game.php') ?>
                            </div>
                            <!--END: MINI GAME -->
                        <?php } ?>


                        <!-- popular -->
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">Most</span> Popular</span></h4>

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

                </div>
                <!-- END: Sidebar2 -->


            </div>

            <!-- END: SPECIAL OFFERS, GEAR PC, TABBED NEWS, RECOMMENDED, POPULAR -->

            <div class="nk-gap-3"></div>

            <!-- START: CATEGORY GAME -->
            <h3 id="CATEGORY_GAME" class="nk-decorated-h-2"><span><span class="text-main-1">CATEGORY</span> GAME</span>
            </h3>

            <div class="nk-gap"></div>

            <div class="nk-carousel nk-carousel-x2" data-autoplay="2000" data-dots="false" data-cell-align="left"
                data-arrows="true">

                <div class="nk-carousel-inner">
                    <?php
                    $sql_category = "SELECT * FROM `genres` LIMIT 16";

                    $result_category = $conn->query($sql_category);
                    while ($row_category = $result_category->fetch_assoc()) { ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="nk-blog-post position-relative" style="margin-left: 20px;">
                                <a href="../Galaxy_Game_Store/store?id_category=<?= $row_category['id'] ?>"
                                    class="nk-post-img">
                                    <img src="./uploads/genre_<?= $row_category['id'] ?>.png"
                                        alt="<?= $row_category['genre_name'] ?>">
                                    <span class="span-genre position-absolute">
                                        <?= $row_category['genre_name'] ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- END: CATEGORY GAME -->
        </div>

        <div class="nk-gap-3"></div>

        <!-- START: Footer -->
        <?php include "./mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>


    <!-- START: Scripts -->
    <?php include "./mod/add_script.php"; ?>
    <!-- END: Scripts -->


</body>

</html>