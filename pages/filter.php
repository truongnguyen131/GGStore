<?php
session_start();
include_once('../mod/database_connection.php');


$classify = isset($_POST['postData']['classify_product']) ? $_POST['postData']['classify_product'] : "all";
$list_genres = isset($_POST['postData']['list_genres']) ? $_POST['postData']['list_genres'] : "";
$list_genres_string = is_array($list_genres) ? implode(",", $list_genres) : $list_genres;
$price = isset($_POST['postData']['price']) ? $_POST['postData']['price'] : "all";
$search = isset($_POST['postData']['search']) ? $_POST['postData']['search'] : "";
$currentPage = isset($_POST['postData']['currentPage']) ? $_POST['postData']['currentPage'] : "";

if ($currentPage != "" && $currentPage > 1) {
    $page = $_POST['postData']['currentPage'];
} else {
    $page = 1;
}
if ($page == 1) {
    $begin = 0;
} else {
    $begin = ($page * 8) - 8;
}

$nums_product = 0;
$nums_exchange = 0;

if ($classify == "exchange" || $classify == "all") {
    $sql_filter_exchange = "SELECT p.id,p.product_name,p.image_avt_url,pp.price,pp.quantity, pp.id as PPID,
    IF(pc.product_id IS NULL, 0, ROUND( AVG(rating) ) ) AS avg_star
    FROM purchased_products pp      
    LEFT JOIN products p ON p.id = pp.product_id
    LEFT JOIN genre_product gp ON p.id = gp.product_id
    LEFT JOIN product_comments pc ON p.id = pc.product_id
    WHERE p.release_date <= NOW() AND pp.status = 'trading'";

    if ($classify == "game" || $classify == "gear") {
        $sql_filter_exchange .= " AND p.classify = '$classify'";
    }
    if ($search != "") {
        $sql_filter_exchange .= " AND p.product_name LIKE '%$search%'";
    }
    if ($price != "all") {
        $sql_filter_exchange .= " AND p.price <= $price";
    }
    if ($list_genres_string != "") {
        $sql_filter_exchange .= " AND gp.genre_id IN ($list_genres_string)";
    }

    $sql_filter_exchange .= " GROUP BY p.id, pp.id";
    $result_filter_exchange = $conn->query($sql_filter_exchange);
    $nums_exchange = $result_filter_exchange->num_rows;
    $total_num_rows = $nums_exchange;
}

if ($classify != "exchange") {
    $sql_filter_product = "SELECT p.id,p.product_name,p.image_avt_url,p.price,d.discount_amount,
    IF(
      d.product_id IS NULL,
      p.price, 
      ROUND(p.price * (1 - d.discount_amount/100))
    ) AS price_new,
    IF(
      d.product_id IS NULL,
      0,
      IF(CURDATE() BETWEEN d.start_date AND d.end_date, 1, 0) 
    ) AS is_discounted,
    IF(pc.product_id IS NULL, 0, ROUND( AVG(rating) ) ) AS avg_star
    FROM products p          
    LEFT JOIN genre_product gp ON p.id = gp.product_id
    LEFT JOIN discounts d ON p.id = d.product_id
    LEFT JOIN product_comments pc ON p.id = pc.product_id
    WHERE p.release_date <= NOW()";

    if ($classify == "game" || $classify == "gear") {
        $sql_filter_product .= " AND p.classify = '$classify'";
    }
    if ($search != "") {
        $sql_filter_product .= " AND p.product_name LIKE '%$search%'";
    }
    if ($price != "all") {
        $sql_filter_product .= " AND p.price <= $price";
    }
    if ($list_genres_string != "") {
        $sql_filter_product .= " AND gp.genre_id IN ($list_genres_string)";
    }

    $sql_filter_product .= " GROUP BY p.id";
    $result_filter_product = $conn->query($sql_filter_product);
    $nums_product = $result_filter_product->num_rows;
    $total_num_rows = $nums_product;
}

$total_page = ceil($total_num_rows / 8);

if ($currentPage > $total_page) {
    $currentPage = 1;
    $begin = 0;
}

if ($nums_product > 0) {
    $sql_filter_product .= " LIMIT $begin, 8";
    $result_filter_product = $conn->query($sql_filter_product);
    $nums_product = $result_filter_product->num_rows;
}
if ($nums_exchange > 0) {
    $sql_filter_exchange .= " LIMIT $begin, 8";
    $result_filter_exchange = $conn->query($sql_filter_exchange);
    $nums_exchange = $result_filter_exchange->num_rows;
}
?>



<div class="row vertical-gap">

    <?php if ($nums_product > 0) {
        while ($row_product = $result_filter_product->fetch_assoc()) { ?>

            <div class="col-lg-3 col-sm-6">
                <div class="nk-gallery-item-box">
                    <a href="../Galaxy_Game_Store/product_details?id=<?= $row_product['id'] ?>" class="nk-gallery-item">
                        <img src="./uploads/<?= $row_product['image_avt_url'] ?>" alt="<?= $row_product['product_name'] ?>">
                    </a>
                    <div class="nk-gap"></div>
                    <h2 class="nk-post-title h4">
                        <a href="../Galaxy_Game_Store/product_details?id=<?= $row_product['id'] ?>"
                            title="<?= $row_product['product_name'] ?>">
                            <?= $row_product['product_name'] ?>
                        </a>
                    </h2>
                    <div class="nk-product-rating">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <i class="<?= ($i <= $row_product['avg_star']) ? 'fa fa-star' : 'far fa-star' ?>"></i>
                        <?php } ?>
                    </div>
                    <div style="font-size: 0.8em;" class="mt-3 mb-5">Genre:
                        <?php $product_id = $row_product['id'];
                        $genres = $conn->query("SELECT g.genre_name,g.id FROM genre_product gp, genres g WHERE g.id = gp.genre_id AND gp.product_id = $product_id")->fetch_all(MYSQLI_ASSOC); ?>
                        <?php foreach ($genres as $genre) { ?>
                            <a href="../Galaxy_Game_Store/store?id_category=<?= $genre['id'] ?>">
                                <?= $genre['genre_name'] ?>
                            </a>
                        <?php } ?>
                    </div>


                    <?php
                    if ($row_product['is_discounted'] == 1) { ?>
                        <span class="price_discount"><i class="fas fa-tag-alt"></i>
                            <?= $row_product['discount_amount'] ?>%
                        </span>&nbsp;
                        <span class="old-price">
                            <?= $row_product['price'] ?><i class="fas fa-gem"></i>
                        </span>&nbsp;
                        <span class="current-price">
                            <?= $row_product['price_new'] ?><i class="fas fa-gem"></i>
                        </span>
                    <?php } else { ?>
                        <span class="price">
                            <?= $row_product['price'] ?><i class="fas fa-gem"></i>
                        </span>
                    <?php } ?>
                </div>
                <div class="nk-gap"></div>
                <div class="row">
                    <div class="col-md-6"><a href="javascript:add_to_cart(<?= $row_product['id'] ?>)"
                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ADD TO
                            CART</a></div>
                    <div class="col-md-6"><a href="javascript:by_now(<?= $row_product['id'] ?>)"
                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">BUY</a>
                    </div>
                </div>
                <div class="nk-gap"></div>
            </div>

        <?php }
    } ?>

    <?php if ($nums_exchange > 0) {
        while ($row_exchange = $result_filter_exchange->fetch_assoc()) { ?>

            <div class="col-lg-3 col-sm-6">
                <div class="nk-gallery-item-box">
                    <a href="../Galaxy_Game_Store/product_details?id=<?= $row_exchange['id'] ?>" class="nk-gallery-item">
                        <img src="./uploads/<?= $row_exchange['image_avt_url'] ?>" alt="<?= $row_exchange['product_name'] ?>">
                    </a>
                    <div class="nk-gap"></div>
                    <h2 class="nk-post-title h4">
                        <a href="../Galaxy_Game_Store/product_details?id=<?= $row_exchange['id'] ?>"
                            title="<?= $row_exchange['product_name'] ?>">
                            <?= $row_exchange['product_name'] ?>
                        </a>
                        x
                        <?= $row_exchange['quantity'] ?>
                    </h2>

                    <div class="nk-product-rating">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <i class="<?= ($i <= $row_exchange['avg_star']) ? 'fa fa-star' : 'far fa-star' ?>"></i>
                        <?php } ?>
                    </div>
                    <div style="font-size: 0.8em;" class="mt-3 mb-5">Genre:
                        <?php $product_id = $row_exchange['id'];
                        $genres = $conn->query("SELECT g.genre_name FROM genre_product gp, genres g WHERE g.id = gp.genre_id AND gp.product_id = $product_id")->fetch_all(MYSQLI_ASSOC); ?>
                        <?php foreach ($genres as $genre) { ?>
                            <a href="">
                                <?= $genre['genre_name'] ?>
                            </a>
                        <?php } ?>
                    </div>


                    <span class="price">
                        <?= $row_exchange['price'] ?><i class="fas fa-gem"></i>
                    </span>

                </div>
                <div class="nk-gap"></div>
                <div class="row">
                    <div class="col-md-6"><a href="javascript:add_to_cart1(<?= $row_exchange['PPID'] ?>)"
                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ADD TO
                            CART</a></div>
                    <div class="col-md-6"><a href="javascript:by_now1(<?= $row_exchange['PPID'] ?>)"
                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">BUY</a>
                    </div>
                </div>
                <div class="nk-gap"></div>
            </div>

        <?php }
    } ?>

    <?php if ($nums_exchange == 0 && $nums_product == 0) { ?>
        <div class="col-lg-12" style="text-align: center;color: #dd163b;font-weight: bold;">No results found</div>
    <?php } ?>
</div>

<!-- START: Pagination -->
<div class="nk-gap-1"></div>
<div class="nk-pagination nk-pagination-center">
    <a href="javascript:prevBtn()" class="nk-pagination-prev">
        <span class="ion-ios-arrow-back"></span>
    </a>
    <nav id="Pagination">
        <?php for ($i = 1; $i <= $total_page; $i++) { ?>
            <a class="<?= ($i == $currentPage) ? "nk-pagination-current" : "" ?>" id="page_<?= $i ?>"
                href="javascript:change_page('page_<?= $i ?>')">
                <?= $i ?>
            </a>
        <?php } ?>
    </nav>
    <a href="javascript:nextBtn()" class="nk-pagination-next">
        <span class="ion-ios-arrow-forward"></span>
    </a>
</div>
<!-- END: Pagination -->