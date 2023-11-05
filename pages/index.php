<?php session_start();
include_once('./mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Galaxy Game Store</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="includes/css/index.css">
    <link rel="stylesheet" href="includes/css/navbar.css">
    <link rel="stylesheet" href="includes/css/header.css">
    <link rel="stylesheet" href="includes/css/footer.css">
    <link rel="stylesheet" href="includes/css/swipper.css">
    <link rel="stylesheet" href="includes/css/slick_slider.css">
    <link rel="stylesheet" href="includes/css/card.css">
</head>

<body>
    <!-- custom scrollbar -->
    <div class="progress">
        <div class="progress_bar" id="scroll_bar"></div>
    </div>
    <div class="header">
        <!-- navbar -->
        <div class="nav" id="navbar">
            <div class="logo">
                <a href="">
                    <span class="logo_gg">
                        GG<span class="logo_store">Store</span>
                    </span>
                </a>
            </div>
            <ul class="nav_list">
                <a href="">
                    <li class="nav_item">Home</li>
                </a>
                <li class="nav_item dropdown-trigger" onmouseover="showDropdown()">Product
                    <ul class="dropdown-content" id="submenu">
                        <a href="">
                            <li>Page 1</li>
                        </a>
                        <a href="">
                            <li>Page 2</li>
                        </a>
                        <a href="">
                            <li>Page 3</li>
                        </a>
                        <a href="">
                            <li>Page 4</li>
                        </a>
                        <a href="">
                            <li>Page 5</li>
                        </a>
                    </ul>
                </li>
                <li class="nav_item cart">Cart</li>
                <li class="nav_item stores">Store</li>
                <li class="nav_item">Blog</li>
                <li class="icon">
                    <i class="fa-solid fa-magnifying-glass icon_size" id="search"></i>
                </li>
                <li class="icon basket">
                    <a href="">
                        <i class="fa-solid fa-basket-shopping icon_size"></i>
                    </a>
                </li>
                <li class="icon store">
                    <a href="">
                        <i class="fa-solid fa-store icon_size"></i>
                    </a>
                </li>
                <li class="icon">
                    <i class="fa-solid fa-bell" id="bell"></i>
                </li>
                <li class="icon">
                    <i class="fa-solid fa-user icon_size" id="user"></i>
                </li>
                <li class="menu_icon">
                    <div class="line1"></div>
                    <div class="line2"></div>
                    <div class="line3"></div>
                </li>
                <!-- menu of search -->
                <div class="menu_search">
                    <input type="text" placeholder="Search" class="bar_search" id="bar_search">
                </div>
                <!-- menu of nofication -->
                <div class="menu_nofication">
                    <!-- item nofication -->
                    <div class="box_nofication">
                        <span class="user_name">Truong </span><span>bought your item on the exchange</span>
                        <p>your account is added 20 GCoin</p>
                        <div class="control_nofication">
                            <p class="announcement_date">12/3/2023 12:30</p>
                            <a href="" class="delete_nofication">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- menu of user -->
                <div class="menu_user">
                    <!-- <div class="menu_us_infor">
                        <span>Hello !</span>
                        <span class="name_user">ngoc truong</span>
                        <div class="coin">
                            <span class="currency_unit">GCoin: </span>
                            <span class="number_gcoin"> 321 <img src="images/Texture_Store_01.png" alt=""
                                    class="represent"></span>
                        </div>
                    </div> -->
                    <div class="menu_us_control">

                        <?php
                        if (isset($_SESSION["role"]) && $_SESSION["role"] == "developer") {
                            echo '<a href="./admin/pages/template/dashboard.php" class="btn_control">Administrator</a>';
                        }
                        if (isset($_SESSION["role"]) && $_SESSION["role"] != "developer") { ?>
                            <a href="" class="btn_control">Information User</a>
                            <a href="" class="btn_control">Your Bag</a>
                            <a href="" class="btn_control">Orders Detail</a>
                            <a href="./pages/logout.php" class="btn_control">Log Out</a>
                        <?php }
                        if (!isset($_SESSION["role"])) {
                            echo '<a href="/Galaxy_Game_Store/login" class="btn_control">Login</a>';
                        }
                        ?>

                    </div>
                </div>
                <!-- menu for mobile -->
                <div class="menu">
                    <img src="images/T_Talisman_xinglong.png" alt="">
                    <div class="navbar">
                        <li><a href="">Home</a></li>
                        <li><a href="">Pages</a></li>
                        <li class="cart_mobie"><a href="">Cart</a></li>
                        <li class="store_mobie"><a href="">Store</a></li>
                        <li><a href="">Blog</a></li>
                    </div>
                </div>
            </ul>

            <div class="lanterns_left" id="lanterns_left">
                <img src="images/first_recharge_tab2.png" alt="" />
            </div>
            <div class="lanterns_right" id="lanterns_right">
                <img src="images/first_recharge_tab2.png" alt="" />
            </div>
        </div>
        <div class="header_content" id="top">
            <div class="header_title">
                <h1>Index</h1>
            </div>
        </div>
        <div class="parallax">
            <img src="images/gonggong_guanxingtai_tijiyun.png" alt="" class="cloud1" id="cl1">
            <img src="images/gonggong_guanxingtai_tijiyun.png" alt="" class="cloud2" id="cl2">
            <img src="images/gonggong_guanxingtai_tijiyun.png" alt="" class="cloud3" id="cl3">
            <img src="images/gonggong_guanxingtai_tijiyun.png" alt="" class="cloud4" id="cl4">
        </div>
    </div>
    <!-- maybe you will like -->
    <div class="you_like_container">
        <div class="you_like_content">
            <div class="list_gift">
                <div class="bling_container">
                    <div class="bag_gift" onclick="addAnimation(1)">
                        <img class="close_gift" src="images/T_FuBenNew_26.png" alt="">
                        <img class="open_gift" src="images/T_FuBenNew_27.png" alt="" style="display: none;">
                    </div>
                    <a href="">
                        <div class="gift" style="display: none;">
                            <div class="img_game">
                                <img src="images/g2.jpg" alt="">
                            </div>
                            <p>Call of Duty</p>
                            <div class="price_gift">
                                <span class="old_price">
                                    15 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                                <span class="new_price">
                                    5 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="bling_container">
                    <div class="bag_gift" onclick="addAnimation(1)">
                        <img class="close_gift" src="images/T_FuBenNew_26.png" alt="">
                        <img class="open_gift" src="images/T_FuBenNew_27.png" alt="" style="display: none;">
                    </div>
                    <a href="">
                        <div class="gift" style="display: none;">
                            <div class="img_game">
                                <img src="images/g5.jpg" alt="">
                            </div>
                            <p>Call of Duty</p>
                            <div class="price_gift">
                                <span class="old_price">
                                    15 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                                <span class="new_price">
                                    5 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="bling_container">
                    <div class="bag_gift" onclick="addAnimation(1)">
                        <img class="close_gift" src="images/T_FuBenNew_26.png" alt="">
                        <img class="open_gift" src="images/T_FuBenNew_27.png" alt="" style="display: none;">
                    </div>
                    <a href="">
                        <div class="gift" style="display: none;">
                            <div class="img_game">
                                <img src="images/g7.jpg" alt="">
                            </div>
                            <p>Call of Duty</p>
                            <div class="price_gift">
                                <span class="old_price">
                                    15 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                                <span class="new_price">
                                    5 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="bling_container">
                    <div class="bag_gift" onclick="addAnimation(1)">
                        <img class="close_gift" src="images/T_FuBenNew_26.png" alt="">
                        <img class="open_gift" src="images/T_FuBenNew_27.png" alt="" style="display: none;">
                    </div>
                    <a href="">
                        <div class="gift" style="display: none;">
                            <div class="img_game">
                                <img src="images/g14.jpg" alt="">
                            </div>
                            <p>Call of Duty</p>
                            <div class="price_gift">
                                <span class="old_price">
                                    15 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                                <span class="new_price">
                                    5 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="bling_container">
                    <div class="bag_gift" onclick="addAnimation(1)">
                        <img class="close_gift" src="images/T_FuBenNew_26.png" alt="">
                        <img class="open_gift" src="images/T_FuBenNew_27.png" alt="" style="display: none;">
                    </div>
                    <a href="">
                        <div class="gift" style="display: none;">
                            <div class="img_game">
                                <img src="images/g1.jpg" alt="">
                            </div>
                            <p>Call of Duty</p>
                            <div class="price_gift">
                                <span class="old_price">
                                    15 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                                <span class="new_price">
                                    5 <img src="images/Texture_Store_01.png" alt="">
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="btn_close close" title="Close">
            <img src="images/btn_common_close3_1.png" alt="">
        </div>
    </div>
    <div class="btn_open_you-will-like">
        <a class="button open" title="Maybe you will like">
            <img src="images/170104.png" alt="">
        </a>
    </div>
    <!-- recommended -->
    <div class="recommended_game containers">
        <!-- title -->
        <div class="recomment_title">
            <h1 class="title">recommended <span class="title_game">game</span></h1>
            <img src="images/title_bar03.png" alt="">
        </div>
        <!-- slider -->
        <section id="tranding">
            <div class="container">
                <div class="swiper tranding-slider">
                    <div class="swiper-wrapper">
                        <!-- Slide-start -->
                        <div class="swiper-slide tranding-slide">
                            <a href="#">
                                <div class="tranding-slide-img">
                                    <img src="images/g1.jpg" alt="Tranding">
                                    <video src="images/g5.mp4" class="hover-video" muted autoplay loop></video>
                                </div>
                            </a>
                        </div>
                        <!-- Slide-end -->
                        <!-- Slide-start -->
                        <div class="swiper-slide tranding-slide">
                            <div class="tranding-slide-img">
                                <img src="images/g14.jpg" alt="Tranding">
                            </div>
                        </div>
                        <!-- Slide-end -->
                        <!-- Slide-start -->
                        <div class="swiper-slide tranding-slide">
                            <div class="tranding-slide-img">
                                <img src="images/g2.jpg" alt="Tranding">
                            </div>
                        </div>
                        <!-- Slide-end -->
                        <!-- Slide-start -->
                        <div class="swiper-slide tranding-slide">
                            <div class="tranding-slide-img">
                                <img src="images/g5.jpg" alt="Tranding">
                            </div>
                        </div>
                        <!-- Slide-end -->
                        <!-- Slide-start -->
                        <div class="swiper-slide tranding-slide">
                            <div class="tranding-slide-img">
                                <img src="images/g7.jpg" alt="Tranding">
                            </div>
                        </div>
                        <!-- Slide-end -->
                        <!-- Slide-start -->
                        <div class="swiper-slide tranding-slide">
                            <div class="tranding-slide-img">
                                <img src="images/g1.jpg" alt="Tranding">
                            </div>
                        </div>
                        <!-- Slide-end -->
                        <!-- Slide-start -->
                        <div class="swiper-slide tranding-slide">
                            <div class="tranding-slide-img">
                                <img src="images/g14.jpg" alt="Tranding">
                            </div>
                        </div>
                        <!-- Slide-end -->
                    </div>
                    <div class="tranding-slider-control">
                        <div class="swiper-button-prev slider-arrow arrow-left">
                            <img src="images/tb_jiantou_fb.png" alt="">
                        </div>
                        <div class="swiper-button-next slider-arrow">
                            <img src="images/tb_jiantou_fb.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- sale -->
    <div class="sale_game containers">
        <div class="favorite_title">
            <div class="title">
                <h1>discount <span class="title_game">game</span></h1>
                <img src="images/title_bar03.png" alt="">
            </div>
            <div class="btn_all_game">
                <a href="">See More</a>
            </div>
        </div>
        <div class="list_card">
            <div class="card">
                <a href="">
                    <img src="images/g1.jpg" alt="">
                    <div class="circle_type">
                        <i class=" fa-solid fa-gamepad "></i>
                    </div>
                    <div class="card_content">
                        <div class="sale">
                            <span>90%</span>
                        </div>
                        <h2>Trailmakers</h2>
                        <p class="star">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </p>
                        <span class="category">
                            Category:
                            <a href="" class="cate_item">Act</a>
                            <a href="" class="cate_item">Act</a>
                        </span>
                        <div class="price">
                            <h4 class="price_old">12 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h4>
                            <h3 class="price_new">10 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h3>
                        </div>
                        <div class="control_card">
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </a>
                        </div>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href=" ">
                    <img src="images/g5.jpg " alt=" ">
                    <div class="circle_type">
                        <i class="fa-solid fa-key "></i>
                    </div>
                    <div class="card_content">
                        <div class="sale">
                            <span>90%</span>
                        </div>
                        <h2>Black mesa</h2>
                        <p class="star">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </p>
                        <span class="category">
                            Category:
                            <a href="" class="cate_item">Act</a>
                            <a href="" class="cate_item">Act</a>
                        </span>
                        <div class="price">
                            <h4 class="price_old">12 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h4>
                            <h3 class="price_new">10 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h3>
                        </div>
                        <div class="control_card">
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </a>
                        </div>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/g14.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/g1.jpg" alt="">
                    <div class="circle_type">
                        <i class=" fa-solid fa-gamepad "></i>
                    </div>
                    <div class="card_content">
                        <div class="sale">
                            <span>90%</span>
                        </div>
                        <h2>Trailmakers</h2>
                        <p class="star">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </p>
                        <span class="category">
                            Category:
                            <a href="" class="cate_item">Act</a>
                            <a href="" class="cate_item">Act</a>
                        </span>
                        <div class="price">
                            <h4 class="price_old">12 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h4>
                            <h3 class="price_new">10 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h3>
                        </div>
                        <div class="control_card">
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </a>
                        </div>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href=" ">
                    <img src="images/g5.jpg " alt=" ">
                    <div class="circle_type">
                        <i class="fa-solid fa-key "></i>
                    </div>
                    <div class="card_content">
                        <div class="sale">
                            <span>90%</span>
                        </div>
                        <h2>Black mesa</h2>
                        <p class="star">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </p>
                        <span class="category">
                            Category:
                            <a href="" class="cate_item">Act</a>
                            <a href="" class="cate_item">Act</a>
                        </span>
                        <div class="price">
                            <h4 class="price_old">12 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h4>
                            <h3 class="price_new">10 <img src="images/Texture_Store_01.png" alt="" class="represent">
                            </h3>
                        </div>
                        <div class="control_card">
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <a href="" class="btn_card">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </a>
                        </div>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/g14.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- gear -->
    <div class="sale_game containers">
        <div class="favorite_title">
            <div class="title">
                <h1>gear <span class="title_game">game</span></h1>
                <img src="images/title_bar03.png" alt="">
            </div>
            <div class="btn_all_game">
                <a href="">See More</a>
            </div>
        </div>
        <div class="list_card">
            <div class="card">
                <a href="">
                    <img src="images/s_product_img04.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/s_product_img04.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/s_product_img04.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/s_product_img04.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/s_product_img04.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="images/s_product_img04.jpg" alt="">
                    <div class="circle_type">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- favorite -->
    <div class="recommended_game containers">
        <!-- title -->
        <div class="recomment_title">
            <h1 class="title">favorite <span class="title_game">game</span></h1>
            <img src="images/title_bar03.png" alt="">
        </div>
        <!-- slider -->
        <div class="image-slider">
            <div class="image-item">
                <div class="item_top">
                    <img src="images/g1.jpg" alt="">
                </div>
                <div class="item_bottom">
                    <div class="star">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2 class="name">Call of Duty</h2>
                    <div class="category">
                        <span>Category:</span>
                        <a href="">Act</a>
                        <a href="">Act</a>
                    </div>
                    <div class="price">
                        <div class="new"><span>10</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                        <div class="old"><span>12</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                    </div>
                    <div class="item_control">
                        <a href="">
                            <i class="fa-solid fa-download"></i>
                        </a>
                        <a href="">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="image-item">
                <div class="item_top">
                    <img src="images/g2.jpg" alt="">
                </div>
                <div class="item_bottom">
                    <div class="star">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2 class="name">Call of Duty</h2>
                    <div class="category">
                        <span>Category:</span>
                        <a href="">Act</a>
                        <a href="">Act</a>
                    </div>
                    <div class="price">
                        <div class="new"><span>10</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                        <div class="old"><span>12</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                    </div>
                    <div class="item_control">
                        <a href="">
                            <i class="fa-solid fa-download"></i>
                        </a>
                        <a href="">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="image-item">
                <div class="item_top">
                    <img src="images/g5.jpg" alt="">
                </div>
                <div class="item_bottom">
                    <div class="star">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2 class="name">Call of Duty</h2>
                    <div class="category">
                        <span>Category:</span>
                        <a href="">Act</a>
                        <a href="">Act</a>
                    </div>
                    <div class="price">
                        <div class="new"><span>10</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                        <div class="old"><span>12</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                    </div>
                    <div class="item_control">
                        <a href="">
                            <i class="fa-solid fa-download"></i>
                        </a>
                        <a href="">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="image-item">
                <div class="item_top">
                    <img src="images/g7.jpg" alt="">
                </div>
                <div class="item_bottom">
                    <div class="star">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2 class="name">Call of Duty</h2>
                    <div class="category">
                        <span>Category:</span>
                        <a href="">Act</a>
                        <a href="">Act</a>
                    </div>
                    <div class="price">
                        <div class="new"><span>10</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                        <div class="old"><span>12</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                    </div>
                    <div class="item_control">
                        <a href="">
                            <i class="fa-solid fa-download"></i>
                        </a>
                        <a href="">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="image-item">
                <div class="item_top">
                    <img src="images/g14.jpg" alt="">
                </div>
                <div class="item_bottom">
                    <div class="star">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2 class="name">Call of Duty</h2>
                    <div class="category">
                        <span>Category:</span>
                        <a href="">Act</a>
                        <a href="">Act</a>
                    </div>
                    <div class="price">
                        <div class="new"><span>10</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                        <div class="old"><span>12</span> <img src="images/Texture_Store_01.png" alt=""
                                class="represent">
                        </div>
                    </div>
                    <div class="item_control">
                        <a href="">
                            <i class="fa-solid fa-download"></i>
                        </a>
                        <a href="">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- catagory -->
    <div class="catagory_game containers">
        <!-- title -->
        <div class="recomment_title">
            <h1 class="title">catagory <span class="title_game">game</span></h1>
            <img src="images/title_bar03.png" alt="">
        </div>
        <!-- slider -->
        <div class="catagory-slider">
            <div class="catagory-item">
                <a href="">
                    <div class="image">
                        <h2>Adventure</h2>
                    </div>
                </a>
            </div>
            <div class="catagory-item">
                <a href="">
                    <div class="image">
                        <h2>Adventure</h2>
                    </div>
                </a>
            </div>
            <div class="catagory-item">
                <a href="">
                    <div class="image">
                        <h2>Adventure</h2>
                    </div>
                </a>
            </div>
            <div class="catagory-item">
                <a href="">
                    <div class="image">
                        <h2>Adventure</h2>
                    </div>
                </a>
            </div>
            <div class="catagory-item">
                <a href="">
                    <div class="image">
                        <h2>Adventure</h2>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="footer">
        <div class="footer_item">
            <div class="footer_logo">
                <a href="">
                    <span class="logo_gg">
                        GG<span class="logo_store">Store</span>
                    </span>
                </a>
            </div>
            <div class="content">
                <span><i class="fa-solid fa-location-dot"></i> Address:CanTho,VietNam</span>
                <span><i class="fa-solid fa-phone"></i> Phone:+84 123 456 789</span>
                <span><i class="fa-solid fa-envelope"></i> Email:info@gmail.com</span>
            </div>
        </div>
        <div class="footer_item">
            <h3 class="title">Pages</h3>
            <div class="content">
                <a href="">Home</a>
                <a href="">Home</a>
                <a href="">Home</a>
                <a href="">Home</a>
                <a href="">Home</a>
            </div>
        </div>
        <div class="footer_item">
            <h3 class="title">Need Help?</h3>
            <div class="content">
                <a href="">Home</a>
                <a href="">Home</a>
                <a href="">Home</a>
                <a href="">Home</a>
                <a href="">Home</a>
            </div>
        </div>
        <div class="footer_item">
            <h3 class="title">Follow us</h3>
            <div class="social">
                <a href="">
                    <img src="images/facebook.png" alt="">
                </a>
                <a href="">
                    <img src="images/twitter.png" alt="">
                </a>
                <a href="">
                    <img src="images/telegram.png" alt="">
                </a>
                <a href="">
                    <img src="images/instagram.png" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="footer_bottom">
        <p>Copyright © 2023 <span class="ggstore">GGSTORE</span> All Rights Reserved.</p>
        <img src="images/card_img.png" alt="">
    </div>
    <div class="button_top" id="scrollToTop">
        <a href="#top" title="Top"><img src="images/504005.png" alt=""></a>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="includes/js/scrollbar.js"></script>
<script src="includes/js/navbar.js"></script>
<script src="includes/js/menu.js"></script>
<script src="includes/js/header.js"></script>
<script src="includes/js/smooth_scrolling.js"></script>
<script src="includes/js/swipper.js"></script>
<script src="includes/js/button_top.js"></script>

<script>
    // favorite game
    var swiper = new Swiper(".favorite-game", {
        direction: "vertical",
        slidesPerView: 1,
        spaceBetween: 30,
        mousewheel: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>

<script>
    function addAnimation(n) {
        const audio = new Audio("../sound/summit_task.wav")
        setTimeout(function () {
            audio.play();
        }, 3000);
        const head = document.head || document.getElementsByTagName('head')[0];
        const styleElement = document.createElement('style');
        const clickedBag = event.target.closest('.bag_gift');
        clickedBag.classList.add('bag-clicked');
        styleElement.textContent = `
        .bag-clicked::after {
        content: '';
        position: absolute;
        z-index: 5;
        background-image: url(images/a104.png);
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        animation: open_gift_bag 4s linear;
    }
`;

        head.appendChild(styleElement);

        setTimeout(function () {
            head.removeChild(styleElement);
        }, 5000);

        const clickedContainer = event.target.closest('.bling_container');
        const close = clickedContainer.querySelector('.close_gift');
        const open = clickedContainer.querySelector('.open_gift');
        const bag = clickedContainer.querySelector('.bag_gift');
        const gift = clickedContainer.querySelector('.gift');

        setTimeout(function () {
            close.style.display = 'none';
            open.style.display = 'block';
        }, 3000);

        setTimeout(function () {
            bag.style.display = 'none';
        }, 4000);
        setTimeout(function () {
            gift.style.display = 'block';
        }, 4050);
    }
</script>

</html>