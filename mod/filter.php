<?php
include_once('../mod/database_connection.php');

$classify = isset($_GET['classify']) ? $_GET['classify'] : "";
$list_genres = isset($_GET['genres']) ? $_GET['genres'] : "";
$price = isset($_GET['price']) ? $_GET['price'] : "";
$search = isset($_GET['search']) ? $_GET['search'] : "";

$sql_product = "SELECT p.*, COALESCE(d.discount_amount, 0) AS discount, gp.genre_id, CASE WHEN d.discount_amount > 0 THEN ROUND(p.price - (p.price * (d.discount_amount * (1/100))),0) ELSE p.price END AS new_price, CASE WHEN pc.id IS NOT NULL THEN CEILING(AVG(pc.rating)) ELSE 2 END AS rating FROM products p LEFT JOIN discounts d ON p.id = d.product_id LEFT JOIN ( SELECT product_id, GROUP_CONCAT(genre_id) AS genre_id FROM genre_product GROUP BY product_id ) gp ON p.id = gp.product_id LEFT JOIN product_comments pc ON p.id = pc.product_id WHERE (d.product_id IS NULL OR (d.start_date <= CURDATE() AND d.end_date >= CURDATE())) AND p.release_date <= NOW()";
$sql_classify = '';
$sql_genre = '';
$sql_price = '';
$sql_search = '';
$sql_groupBy = ' GROUP BY p.id';
$sql_orderBy = ' ORDER BY new_price ASC';
if ($classify == "all") {
    $sql_classify = '';
} else {
    $sql_classify = " AND p.classify = '" . $classify . "'";
}

if ($list_genres != "") {
    $genres = explode(',', $list_genres);
    $sql_genre .= " AND (";
    $conditions = array();
    foreach ($genres as $genre) {
        $conditions[] = "FIND_IN_SET('$genre', gp.genre_id) > 0";
    }
    $sql_genre .= implode(' OR ', $conditions);
    $sql_genre .= ")";
}

if ($price == 'all') {
    $sql_price = '';
} else if ($price != '' && $price == 'over') {
    $sql_price = " HAVING new_price > 300";
} else {
    $sql_price = " HAVING new_price <= $price";
}

if ($search != '') {
    $sql_search = " AND p.product_name LIKE '%" . $search . "%'";
}
$sql = $sql_product . $sql_classify . $sql_genre . $sql_search . $sql_groupBy . $sql_price . $sql_orderBy;
// echo "$sql";
$results = mysqli_query($conn, $sql);
if ($results->num_rows > 0) {
    while ($row = mysqli_fetch_array($results)) {
        echo '<div class="col-lg-3">
            <div class="nk-gallery-item-box">
                <a href="" class="nk-gallery-item">
                    <img src="./uploads/' . $row['image_avt_url'] . '" alt="">
                </a>
                <div class="nk-gap"></div>
                <h2 class="nk-post-title h4">
                    <a href="" title="' . $row['product_name'] . '">
                        ' . $row['product_name'] . '
                    </a>
                </h2>';
        if ($row['rating'] > 0) {
            echo '<div class="nk-product-rating">';
            for ($i = 1; $i <= $row['rating']; $i++) {
                echo '<i class="fa fa-star"></i>';
            }
            echo '</div>';
        } else {
            echo '<div class="nk-product-rating"></div>';
        }
        echo '<div class="mt-5">Genre: ';
        $sql_nameGenre = "SELECT g.genre_name 
        FROM genres g
        JOIN genre_product gp ON g.id = gp.genre_id
        WHERE gp.product_id = '" . $row['id'] . "' GROUP BY g.genre_name";
        
        $nameGenre = mysqli_query($conn, $sql_nameGenre);
        $firstGenre = true; 
        while ($nameGenre_item = mysqli_fetch_array($nameGenre)) {
            if (!$firstGenre) {
                echo ', ';
            }
            echo "<a href=''>".$nameGenre_item['genre_name']."</a>";
            $firstGenre = false;
        }
        
        echo '</div>';
        if ($row['discount'] > 0) {
            echo '<span class="price_discount"><i class="fas fa-tag-alt"></i>' . $row['discount'] . '%
            </span>&nbsp;';
        } else {
            echo '<span class="price_discount" style="display: none;"></span>';
        }
        echo '<span class="old-price">
                        ' . $row['price'] . '<i class="fas fa-gem"></i>
                    </span>&nbsp;
                    <span class="current-price">
                        ' . $row['new_price'] . '<i class="fas fa-gem"></i>
                    </span>
                </div>
                <div class="nk-gap"></div>
                <div class="row">
                    <div class="col-md-6"><a href="" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ADD TO CART</a></div>
                    <div class="col-md-6"><a href="" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">BUY</a></div>
                </div>
                <div class="nk-gap"></div>
            </div>
        </div>
    </div>';
    }
} else {
    echo '<div class="col-lg-12 no_suggestion">No suggestion</div>';
}
