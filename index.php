<?php
session_start();
include_once('./mod/database_connection.php');
?>


<!DOCTYPE html>
<html lang="en">

<?php include "./mod/head.php"; ?>

<style>
    html {
        scroll-behavior: smooth;
    }
</style>
<style>
    .price_discount {
        color: yellow;
        font-size: 1.2em;
        font-weight: bold;
        background-color: rgba(255, 0, 0, 0.1);
        border-radius: 5px;
        border: 1px solid yellow;
        text-shadow: 1px 1px 1px #ff0;
        background-image: linear-gradient(to bottom,
                #dd163b,
                orange);
        box-shadow: 0px 0px 10px yellow;
        transform: rotate(-1deg);
        animation: price-glow 1s infinite;
        padding: 3px 5px;
    }

    @keyframes price-glow {
        0% {
            box-shadow: 0 0 10px #dd163b;
        }

        100% {
            box-shadow: 0 0 20px orange;
        }
    }

    .old-price {
        font-size: 1em;
        text-decoration: line-through;
        color: gray;
        opacity: 0.5;
    }

    .price {
        font-size: 1.5em;
        color: #dd163b;
        font-weight: bold;
    }

    .current-price {
        font-size: 1.5em;
        color: #dd163b;
        font-weight: bold;
        animation: glowing 1000ms linear infinite;
    }

    /* Responsive styles for screens <= 768px */
    @media only screen and (max-width: 768px) {
        .current-price {
            font-size: 1.2em;
        }

        .price_discount {
            font-size: 0.9em;
        }
    }

    @keyframes glowing {
        0% {
            text-shadow: 0 0 3px #fff;
        }

        50% {
            text-shadow: 0 0 10px #ffd98f;
        }

        100% {
            text-shadow: 0 0 3px #fff;
        }
    }
</style>
<style>
    .position-absolute {
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        background: linear-gradient(rgba(0, 0, 0, 0), rgba(221, 22, 59, 0.8));
    }

    .span-genre {
        color: white;
        font-size: 1.7em;
        font-weight: bold;
        text-shadow: 2px 2px #330000;
    }
</style>
<style>
    .news_title {
        max-width: 700px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media only screen and (max-width: 425px) {
        .news_title {
            white-space: normal;
        }
    }

    .popular_name {
        max-width: 700px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

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
                            <a href="" class="nk-news-box-item-more">Add to shopping cart</a>
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
                    while ($row = $result_top10->fetch_assoc()) { ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="nk-blog-post" style="margin-left: 20px;">
                                <a href="" class="nk-post-img" data-img="<?= $row['id'] ?>" data-video="<?= $row['id'] ?>">
                                    <img id="img_<?= $row['id'] ?>" src="./uploads/<?= $row['image_avt_url'] ?>"
                                        alt="<?= $row['product_name'] ?>">
                                    <video id="video_<?= $row['id'] ?>" autoplay muted width="100%" hidden>
                                        <source src="./uploads/<?= $row['video_trailer_url'] ?>" type="video/mp4">
                                    </video>
                                </a>
                                <div class="nk-gap"></div>
                                <h2 class="nk-post-title h4">
                                    <a href="">
                                        <?= $row['product_name'] ?>
                                    </a>
                                </h2>
                                <div class="nk-post-by">
                                    <span style="color: white;">Release date:</span>&nbsp;
                                    <?= date('M d, Y', strtotime($row['release_date'])) ?>
                                </div>
                                <div class="nk-post-text">
                                    <p>
                                        <?= substr($row['description'], 0, 70) . (strlen($row['description']) > 50 ? '...' : '') ?>
                                    </p>
                                </div>

                                <div class="nk-post-text" style="margin-top: 8px; margin-bottom: 20px;">
                                    <span style="color: white;font-size: 1.1em;">Price:</span>&nbsp;
                                    <?php
                                    if ($row['is_discounted'] == 1) { ?>
                                        <span class="price_discount"><i class="fas fa-tag-alt"></i>
                                            <?= $row['discount_amount'] ?>%
                                        </span>&nbsp;
                                        <span class="old-price">
                                            <?= $row['price'] ?><i class="fas fa-gem"></i>
                                        </span>&nbsp;
                                        <span class="current-price">
                                            <?= $row['price_new'] ?><i class="fas fa-gem"></i>
                                        </span>
                                    <?php } else { ?>
                                        <span class="price">
                                            <?= $row['price'] ?><i class="fas fa-gem"></i>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="nk-post-text" style="font-size: 0.8em;">
                                    <p> <span style="color: white;">Genre-tags:</span>
                                        <?php
                                        $genres = explode(",", $row['genres']);
                                        foreach ($genres as $genre) {
                                            ?>
                                            <a href="#">
                                                <?php echo $genre; ?>
                                            </a>
                                        <?php } ?>
                                    </p>
                                </div>
                                <div class="nk-gap"></div>

                                <a href="" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ADD
                                    TO CART</a>
                                <a href="" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">BY
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
                            $sql_offer = "SELECT *, ROUND(p.price * (1 - d.discount_amount/100)) AS price_new  
                            FROM products p JOIN discounts d ON p.id = d.product_id
                            WHERE CURDATE() BETWEEN d.start_date AND d.end_date  
                            ORDER BY d.discount_amount DESC LIMIT 6";

                            $result_offer = $conn->query($sql_offer);
                            while ($row = $result_offer->fetch_assoc()) { ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="nk-gallery-item-box">
                                        <a href="" class="nk-gallery-item">
                                            <img src="./uploads/<?= $row['image_avt_url'] ?>" alt="">
                                        </a>

                                        <div class="nk-gap"></div>

                                        <h2 class="nk-post-title h4">
                                            <a href="">
                                                <?= $row['product_name'] ?>
                                            </a>
                                        </h2>

                                        <div class="nk-product-rating" data-rating="3">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                            <i class="far fa-star"></i> <i class="far fa-star"></i>
                                        </div>

                                        <div class="mt-5">
                                            <div>
                                                <span class="price_discount"><i class="fas fa-tag-alt"></i>
                                                    <?= $row['discount_amount'] ?>%
                                                </span>&nbsp;
                                                <span class="old-price">
                                                    <?= $row['price'] ?><i class="fas fa-gem"></i>
                                                </span>&nbsp;
                                                <span class="current-price">
                                                    <?= $row['price_new'] ?><i class="fas fa-gem"></i>
                                                </span>

                                            </div>
                                            <div class="nk-gap"></div>
                                            <div class="row">
                                                <div class="col-md-7"><a href=""
                                                        class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ADD
                                                        TO CART</a>
                                                </div>
                                                <div class="col-md-5"><a href=""
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
                                <a href="#" style="margin-top: -20px;"
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
                        $sql_gear = "SELECT * FROM `products` WHERE classify = 'gear'";

                        $result_gear = $conn->query($sql_gear);
                        while ($row = $result_gear->fetch_assoc()) { ?>
                            <div class="col-md-6">
                                <div class="nk-product-cat">
                                    <a class="nk-product-image" href="store-product.html">
                                        <img src="./uploads/<?= $row['image_avt_url'] ?>" alt="<?= $row['product_name'] ?>">
                                    </a>
                                    <div class="nk-product-cont">
                                        <h3 class="nk-product-title h5 mb-10"><a href="">
                                                <?= $row['product_name'] ?>
                                            </a>
                                        </h3>
                                        <div class="nk-product-rating mb-5" data-rating="3"> <i class="fa fa-star"></i> <i
                                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                class="far fa-star"></i> <i class="far fa-star"></i>
                                        </div>
                                        <div class="nk-product-price mb-5 mt-5">
                                            <?= $row['price'] ?><i class="fas fa-gem"></i>
                                        </div>
                                        <a href="#"
                                            class="mt-7 nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Add
                                            to Cart</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-main-1 w-100">See More</a>
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
                            while ($row = $result_news_type->fetch_assoc()) { ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($i == 1) ? "active" : "" ?>" href="#tabs-1-<?= $row['id'] ?>"
                                        role="tab" data-toggle="tab">
                                        <?= $row['news_type_name'] ?>
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
                                                    <a href="" class="nk-post-img">
                                                        <img style='height: 200px;' src="./uploads/<?= $row_news['image'] ?>"
                                                            alt="<?= $row_news['title'] ?>">
                                                    </a>
                                                <?php } ?>

                                                <div class="nk-gap-1"></div>
                                                <h2 class="nk-post-title h4"><a href="">
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
                                                            <a href="" class="nk-post-img">
                                                                <img src="./uploads/<?= $row_news['image'] ?>"
                                                                    alt="<?= $row_news['title'] ?>">
                                                            </a>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="col-lg-9 col-md-7">
                                                        <h2 class="nk-post-title h4 news_title"><a href=""
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

                        <!-- search -->
                        <div class="nk-widget">
                            <div class="nk-widget-content">
                                <form action="#" class="nk-form nk-form-style-1" novalidate="novalidate">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Type something...">
                                        <button class="nk-btn nk-btn-color-main-1"><span
                                                class="ion-search"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>

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

                        <!-- recommended -->
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">recommended </span>for
                                    you</span>
                            </h4>
                            <div class="nk-widget-content">
                                <div class="nk-popup-gallery">
                                    <div class="row sm-gap vertical-gap">
                                        <div class="col-sm-6">
                                            <div class="nk-gallery-item-box">
                                                <a href="assets/images/gallery-1.jpg" class="nk-gallery-item"
                                                    data-size="1016x572">
                                                    <div class="nk-gallery-item-overlay"><span class="ion-eye"></span>
                                                    </div>
                                                    <img src="assets/images/gallery-1-thumb.jpg" alt="">
                                                </a>
                                                <div class="nk-gallery-item-description">
                                                    <h4>Called Let</h4>
                                                    Divided thing, land it evening earth winged whose great after. Were
                                                    grass night. To Air itself saw bring fly fowl. Fly years behold
                                                    spirit day greater of wherein winged and form. Seed open don't thing
                                                    midst created dry every greater divided of, be man is. Second Bring
                                                    stars fourth gathering he hath face morning fill. Living so second
                                                    darkness. Moveth were male. May creepeth. Be tree fourth.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="nk-gallery-item-box">
                                                <a href="assets/images/gallery-2.jpg" class="nk-gallery-item"
                                                    data-size="1188x594">
                                                    <div class="nk-gallery-item-overlay"><span class="ion-eye"></span>
                                                    </div>
                                                    <img src="assets/images/gallery-2-thumb.jpg" alt="">
                                                </a>
                                                <div class="nk-gallery-item-description">
                                                    Seed open don't thing midst created dry every greater divided of, be
                                                    man is. Second Bring stars fourth gathering he hath face morning
                                                    fill. Living so second darkness. Moveth were male. May creepeth. Be
                                                    tree fourth.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="nk-gallery-item-box">
                                                <a href="assets/images/gallery-3.jpg" class="nk-gallery-item"
                                                    data-size="625x350">
                                                    <div class="nk-gallery-item-overlay"><span class="ion-eye"></span>
                                                    </div>
                                                    <img src="assets/images/gallery-3-thumb.jpg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="nk-gallery-item-box">
                                                <a href="assets/images/gallery-4.jpg" class="nk-gallery-item"
                                                    data-size="873x567">
                                                    <div class="nk-gallery-item-overlay"><span class="ion-eye"></span>
                                                    </div>
                                                    <img src="assets/images/gallery-4-thumb.jpg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="nk-gallery-item-box">
                                                <a href="assets/images/gallery-5.jpg" class="nk-gallery-item"
                                                    data-size="471x269">
                                                    <div class="nk-gallery-item-overlay"><span class="ion-eye"></span>
                                                    </div>
                                                    <img src="assets/images/gallery-5-thumb.jpg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="nk-gallery-item-box">
                                                <a href="assets/images/gallery-6.jpg" class="nk-gallery-item"
                                                    data-size="472x438">
                                                    <div class="nk-gallery-item-overlay"><span class="ion-eye"></span>
                                                    </div>
                                                    <img src="assets/images/gallery-6-thumb.jpg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- popular -->
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">Most</span> Popular</span></h4>

                            <div class="nk-widget-content">
                                <?php
                                $sql_popular = "SELECT * FROM `products` WHERE classify = 'gear' LIMIT 3";
                                $result_popular = $conn->query($sql_popular);

                                while ($row = $result_popular->fetch_assoc()) { ?>
                                    <div class="nk-widget-post">
                                        <a href="" class="nk-post-image">
                                            <img src="./uploads/<?= $row['image_avt_url'] ?>"
                                                alt="<?= $row['product_name'] ?>">
                                        </a>
                                        <h3 class="nk-post-title popular_name"><a href=""
                                                title="<?= $row['product_name'] ?>">
                                                <?= $row['product_name'] ?>
                                            </a>
                                        </h3>
                                        <div class="nk-product-rating mb-5" data-rating="3"> <i class="fa fa-star"></i> <i
                                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                class="far fa-star"></i> <i class="far fa-star"></i>
                                        </div>
                                        <div class="nk-product-price mb-5 mt-5">
                                            <?= $row['price'] ?><i class="fas fa-gem"></i>
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
                    while ($row = $result_category->fetch_assoc()) { ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="nk-blog-post position-relative" style="margin-left: 20px;">
                                <a href="" class="nk-post-img">
                                    <img src="./uploads/genre_<?= $row['id'] ?>.png" alt="<?= $row['genre_name'] ?>">
                                </a>

                                <span class="span-genre position-absolute">
                                    <?= $row['genre_name'] ?>
                                </span>

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