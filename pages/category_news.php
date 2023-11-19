<?php
session_start();
include_once('../mod/database_connection.php');


$id = $_GET['id'];

$sql = "SELECT * FROM news_type WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$news_type_name = $row["news_type_name"];

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if ($page == 1) {
    $begin = 0;
} else {
    $begin = ($page * 10) - 10;
}

$sql_select_num_rows = "SELECT *, (SELECT COUNT(*) FROM news_comments nc 
                WHERE nc.news_id = n.id) AS comment_count FROM news n WHERE n.news_type_id = $id";
$stmt = $conn->prepare($sql_select_num_rows);
$stmt->execute();

$total_num_rows = $stmt->get_result()->num_rows;
$total_page = ceil($total_num_rows / 10);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>GoodGames | Main Game Discussions</title>

    <meta name="description" content="GoodGames - Bootstrap template for communities and games store">
    <meta name="keywords" content="game, gaming, template, HTML template, responsive, Bootstrap, premium">
    <meta name="author" content="_nK">

    <link rel="icon" type="image/png" href="assets/images/favicon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- START: Styles -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7cOpen+Sans:400,700" rel="stylesheet"
        type="text/css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <script defer src="assets/vendor/fontawesome-free/js/all.js"></script>
    <script defer src="assets/vendor/fontawesome-free/js/v4-shims.js"></script>

    <!-- IonIcons -->
    <link rel="stylesheet" href="assets/vendor/ionicons/css/ionicons.min.css">

    <!-- Flickity -->
    <link rel="stylesheet" href="assets/vendor/flickity/dist/flickity.min.css">

    <!-- Photoswipe -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/photoswipe/dist/photoswipe.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/photoswipe/dist/default-skin/default-skin.css">

    <!-- Seiyria Bootstrap Slider -->
    <link rel="stylesheet" href="assets/vendor/bootstrap-slider/dist/css/bootstrap-slider.min.css">

    <!-- Summernote -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/summernote/dist/summernote-bs4.css">

    <!-- GoodGames -->
    <link rel="stylesheet" href="assets/css/goodgames.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- END: Styles -->

    <!-- jQuery -->
    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>


</head>

<body>


    <header class="nk-header nk-header-opaque">



        <!-- START: Top Contacts -->
        <div class="nk-contacts-top">
            <div class="container">
                <div class="nk-contacts-left">
                    <ul class="nk-social-links">
                        <li><a class="nk-social-rss" href="#"><span class="fa fa-rss"></span></a></li>
                        <li><a class="nk-social-twitch" href="#"><span class="fab fa-twitch"></span></a></li>
                        <li><a class="nk-social-steam" href="#"><span class="fab fa-steam"></span></a></li>
                        <li><a class="nk-social-facebook" href="#"><span class="fab fa-facebook"></span></a></li>
                        <li><a class="nk-social-google-plus" href="#"><span class="fab fa-google-plus"></span></a></li>
                        <li><a class="nk-social-twitter" href="#" target="_blank"><span
                                    class="fab fa-twitter"></span></a></li>
                        <li><a class="nk-social-pinterest" href="#"><span class="fab fa-pinterest-p"></span></a></li>

                        <!-- Additional Social Buttons
                    <li><a class="nk-social-behance" href="#"><span class="fab fa-behance"></span></a></li>
                    <li><a class="nk-social-bitbucket" href="#"><span class="fab fa-bitbucket"></span></a></li>
                    <li><a class="nk-social-dropbox" href="#"><span class="fab fa-dropbox"></span></a></li>
                    <li><a class="nk-social-dribbble" href="#"><span class="fab fa-dribbble"></span></a></li>
                    <li><a class="nk-social-deviantart" href="#"><span class="fab fa-deviantart"></span></a></li>
                    <li><a class="nk-social-flickr" href="#"><span class="fab fa-flickr"></span></a></li>
                    <li><a class="nk-social-foursquare" href="#"><span class="fab fa-foursquare"></span></a></li>
                    <li><a class="nk-social-github" href="#"><span class="fab fa-github"></span></a></li>
                    <li><a class="nk-social-instagram" href="#"><span class="fab fa-instagram"></span></a></li>
                    <li><a class="nk-social-linkedin" href="#"><span class="fab fa-linkedin"></span></a></li>
                    <li><a class="nk-social-medium" href="#"><span class="fab fa-medium"></span></a></li>
                    <li><a class="nk-social-odnoklassniki" href="#"><span class="fab fa-odnoklassniki"></span></a></li>
                    <li><a class="nk-social-paypal" href="#"><span class="fab fa-paypal"></span></a></li>
                    <li><a class="nk-social-reddit" href="#"><span class="fab fa-reddit"></span></a></li>
                    <li><a class="nk-social-skype" href="#"><span class="fab fa-skype"></span></a></li>
                    <li><a class="nk-social-soundcloud" href="#"><span class="fab fa-soundcloud"></span></a></li>
                    <li><a class="nk-social-slack" href="#"><span class="fab fa-slack"></span></a></li>
                    <li><a class="nk-social-tumblr" href="#"><span class="fab fa-tumblr"></span></a></li>
                    <li><a class="nk-social-vimeo" href="#"><span class="fab fa-vimeo"></span></a></li>
                    <li><a class="nk-social-vk" href="#"><span class="fab fa-vk"></span></a></li>
                    <li><a class="nk-social-wordpress" href="#"><span class="fab fa-wordpress"></span></a></li>
                    <li><a class="nk-social-youtube" href="#"><span class="fab fa-youtube"></span></a></li>
                -->
                    </ul>
                </div>
                <div class="nk-contacts-right">
                    <ul class="nk-contacts-icons">

                        <li>
                            <a href="#" data-toggle="modal" data-target="#modalSearch">
                                <span class="fa fa-search"></span>
                            </a>
                        </li>


                        <li>
                            <a href="#" data-toggle="modal" data-target="#modalLogin">
                                <span class="fa fa-user"></span>
                            </a>
                        </li>


                        <li>
                            <span class="nk-cart-toggle">
                                <span class="fa fa-shopping-cart"></span>
                                <span class="nk-badge">27</span>
                            </span>
                            <div class="nk-cart-dropdown">

                                <div class="nk-widget-post">
                                    <a href="store-product.html" class="nk-post-image">
                                        <img src="assets/images/product-5-xs.jpg" alt="In all revolutions of">
                                    </a>
                                    <h3 class="nk-post-title">
                                        <a href="#" class="nk-cart-remove-item"><span
                                                class="ion-android-close"></span></a>
                                        <a href="store-product.html">In all revolutions of</a>
                                    </h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 23.00</div>
                                </div>

                                <div class="nk-widget-post">
                                    <a href="store-product.html" class="nk-post-image">
                                        <img src="assets/images/product-7-xs.jpg" alt="With what mingled joy">
                                    </a>
                                    <h3 class="nk-post-title">
                                        <a href="#" class="nk-cart-remove-item"><span
                                                class="ion-android-close"></span></a>
                                        <a href="store-product.html">With what mingled joy</a>
                                    </h3>
                                    <div class="nk-gap-1"></div>
                                    <div class="nk-product-price">€ 14.00</div>
                                </div>

                                <div class="nk-gap-2"></div>
                                <div class="text-center">
                                    <a href="store-checkout.html"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">Proceed
                                        to Checkout</a>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <nav class="nk-navbar nk-navbar-top nk-navbar-sticky nk-navbar-autohide">
            <div class="container">
                <div class="nk-nav-table">

                    <a href="index.html" class="nk-nav-logo">
                        <img src="assets/images/logo1.png" alt="GoodGames" width="199">
                    </a>

                    <ul class="nk-nav nk-nav-right d-none d-lg-table-cell" data-nav-mobile="#nk-nav-mobile">

                        <li class="active nk-drop-item">
                            <a href="elements.html">
                                Features

                            </a>
                            <ul class="dropdown">

                                <li>
                                    <a href="elements.html">
                                        Elements (Shortcodes)

                                    </a>
                                </li>
                                <li class="active nk-drop-item">
                                    <a href="forum.html">
                                        Forum

                                    </a>
                                    <ul class="dropdown">

                                        <li>
                                            <a href="forum.html">
                                                Forum

                                            </a>
                                        </li>
                                        <li class="active">
                                            <a href="forum-topics.html">
                                                Topics

                                            </a>
                                        </li>
                                        <li>
                                            <a href="forum-single-topic.html">
                                                Single Topic

                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="widgets.html">
                                        Widgets

                                    </a>
                                </li>
                                <li>
                                    <a href="coming-soon.html">
                                        Coming Soon

                                    </a>
                                </li>
                                <li>
                                    <a href="offline.html">
                                        Offline

                                    </a>
                                </li>
                                <li>
                                    <a href="404.html">
                                        404

                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class=" nk-drop-item">
                            <a href="blog-list.html">
                                Blog

                            </a>
                            <ul class="dropdown">

                                <li>
                                    <a href="news.html">
                                        News

                                    </a>
                                </li>
                                <li class=" nk-drop-item">
                                    <a href="blog-grid.html">
                                        Blog With Sidebar

                                    </a>
                                    <ul class="dropdown">

                                        <li>
                                            <a href="blog-grid.html">
                                                Blog Grid

                                            </a>
                                        </li>
                                        <li>
                                            <a href="blog-list.html">
                                                Blog List

                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="blog-fullwidth.html">
                                        Blog Fullwidth

                                    </a>
                                </li>
                                <li>
                                    <a href="blog-article.html">
                                        Blog Article

                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="gallery.html">
                                Gallery

                            </a>
                        </li>
                        <li class=" nk-drop-item">
                            <a href="tournaments.html">
                                Tournaments

                            </a>
                            <ul class="dropdown">

                                <li>
                                    <a href="tournaments.html">
                                        Tournament

                                    </a>
                                </li>
                                <li>
                                    <a href="tournaments-teams.html">
                                        Teams

                                    </a>
                                </li>
                                <li>
                                    <a href="tournaments-teammate.html">
                                        Teammate

                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class=" nk-drop-item">
                            <a href="store.html">
                                Store

                            </a>
                            <ul class="dropdown">

                                <li>
                                    <a href="store.html">
                                        Store

                                    </a>
                                </li>
                                <li>
                                    <a href="store-product.html">
                                        Product

                                    </a>
                                </li>
                                <li>
                                    <a href="store-catalog.html">
                                        Catalog

                                    </a>
                                </li>
                                <li>
                                    <a href="store-catalog-alt.html">
                                        Catalog Alt

                                    </a>
                                </li>
                                <li>
                                    <a href="store-checkout.html">
                                        Checkout

                                    </a>
                                </li>
                                <li>
                                    <a href="store-cart.html">
                                        Cart

                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nk-nav nk-nav-right nk-nav-icons">

                        <li class="single-icon d-lg-none">
                            <a href="#" class="no-link-effect" data-nav-toggle="#nk-nav-mobile">
                                <span class="nk-icon-burger">
                                    <span class="nk-t-1"></span>
                                    <span class="nk-t-2"></span>
                                    <span class="nk-t-3"></span>
                                </span>
                            </a>
                        </li>


                    </ul>
                </div>
            </div>
        </nav>
        <!-- END: Navbar -->

    </header>


    <div id="nk-nav-mobile" class="nk-navbar nk-navbar-side nk-navbar-right-side nk-navbar-overlay-content d-lg-none">
        <div class="nano">
            <div class="nano-content">
                <a href="index.html" class="nk-nav-logo">
                    <img src="assets/images/logo.png" alt="" width="120">
                </a>
                <div class="nk-navbar-mobile-content">
                    <ul class="nk-nav">
                        <!-- Here will be inserted menu from [data-mobile-menu="#nk-nav-mobile"] -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Navbar Mobile -->



    <div class="nk-main">

        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">

                <li><a href="../Galaxy_Game_Store/home">Home</a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="./main_news">News</a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="./category_news?id=<?= $id ?>">
                        <?= $news_type_name ?>
                    </a></li>

                <li><span class="fa fa-angle-right"></span></li>
                <div class="nk-gap-1"></div>
                <li><span>
                        <?= $news_type_name ?>
                    </span></li>

            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->


        <div class="container">

            <div class="nk-gap-2"></div>

            <!-- START: Post List -->
            <ul class="nk-forum">
                <?php
                $sql_news = "SELECT *, (SELECT COUNT(*) FROM news_comments nc 
                WHERE nc.news_id = n.id) AS comment_count FROM `news` n 
                WHERE n.news_type_id = $id LIMIT $begin, 10";
                $result_news = $conn->query($sql_news);
                while ($row = $result_news->fetch_assoc()) { ?>
                    <li>
                        <div class="nk-forum-icon">
                            <?php if ($id == 1) { ?>
                                <span class="ion-flame"></span>
                            <?php } ?>
                            <?php if ($id == 2) { ?>
                                <span class="ion-steam"></span>
                            <?php } ?>
                            <?php if ($id == 3) { ?>
                                <span class="ion-help-buoy"></span>
                            <?php } ?>
                            <?php if ($id == 4) { ?>
                                <span class="ion-chatboxes"></span>
                            <?php } ?>
                            <?php if ($id == 5) { ?>
                                <span class="ion-ios-game-controller-b"></span>
                            <?php } else {
                                echo "";
                            } ?>
                        </div>
                        <div class="nk-forum-title">
                            <h3><a href="../Galaxy_Game_Store/news?id=<?= $row['id'] ?>">
                                    <?= $row['title'] ?>
                                </a></h3>
                        </div>
                        <div class="nk-forum-count">
                            <?= $row['comment_count'] ?> <br>
                            <span class="fa fa-comments"></span> comments
                        </div>

                        <div class="nk-forum-activity">
                            <div class="nk-forum-activity-date">
                                Post on <br>
                                <?= date('M d, Y', strtotime($row['publish_date'])) ?>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <!-- END: Post List -->

            <div class="nk-gap-2"></div>
            <?php if ($total_num_rows != 0) { ?>
                <!-- START: Pagination -->
                <div class="row">
                    <div class="col-md-9">
                        <div class="nk-pagination nk-pagination-left">
                            <a href="./category_news?id=<?= $id ?>&page=<?= ($page > 1) ? $page - 1 : $page; ?>"
                                class="nk-pagination-prev">
                                <span class="ion-ios-arrow-back"></span>
                            </a>
                            <nav>
                                <?php
                                for ($i = 1; $i <= $total_page; $i++) { ?>
                                    <a class="<?= ($i == $page) ? 'nk-pagination-current' : '' ?>"
                                        href="./category_news?id=<?= $id ?>&page=<?= $i ?>">
                                        <?= $i ?>
                                    </a>
                                <?php } ?>
                            </nav>
                            <a href="./category_news?id=<?= $id ?>&page=<?= ($page < $total_page) ? $page + 1 : $page ?>"
                                class="nk-pagination-next">
                                <span class="ion-ios-arrow-forward"></span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Pagination -->
            <?php } ?>

        </div>

        <div class="nk-gap-2"></div>

        <!-- START: Footer -->
        <?php include "../mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>




    <!-- START: Page Background -->

    <img class="nk-page-background-top" src="assets/images/bg-top.png" alt="">
    <img class="nk-page-background-bottom" src="assets/images/bg-bottom.png" alt="">

    <!-- END: Page Background -->




    <!-- START: Search Modal -->
    <div class="nk-modal modal fade" id="modalSearch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ion-android-close"></span>
                    </button>

                    <h4 class="mb-0">Search</h4>

                    <div class="nk-gap-1"></div>
                    <form action="#" class="nk-form nk-form-style-1">
                        <input type="text" value="" name="search" class="form-control"
                            placeholder="Type something and press Enter" autofocus>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Search Modal -->



    <!-- START: Login Modal -->
    <div class="nk-modal modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ion-android-close"></span>
                    </button>

                    <h4 class="mb-0"><span class="text-main-1">Sign</span> In</h4>

                    <div class="nk-gap-1"></div>
                    <form action="#" class="nk-form text-white">
                        <div class="row vertical-gap">
                            <div class="col-md-6">
                                Use email and password:

                                <div class="nk-gap"></div>
                                <input type="email" value="" name="email" class=" form-control" placeholder="Email">

                                <div class="nk-gap"></div>
                                <input type="password" value="" name="password" class="required form-control"
                                    placeholder="Password">
                            </div>
                            <div class="col-md-6">
                                Or social account:

                                <div class="nk-gap"></div>

                                <ul class="nk-social-links-2">
                                    <li><a class="nk-social-facebook" href="#"><span class="fab fa-facebook"></span></a>
                                    </li>
                                    <li><a class="nk-social-google-plus" href="#"><span
                                                class="fab fa-google-plus"></span></a></li>
                                    <li><a class="nk-social-twitter" href="#"><span class="fab fa-twitter"></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="nk-gap-1"></div>
                        <div class="row vertical-gap">
                            <div class="col-md-6">
                                <a href="#" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block">Sign In</a>
                            </div>
                            <div class="col-md-6">
                                <div class="mnt-5">
                                    <small><a href="#">Forgot your password?</a></small>
                                </div>
                                <div class="mnt-5">
                                    <small><a href="#">Not a member? Sign up</a></small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Login Modal -->




    <!-- START: Scripts -->

    <!-- Object Fit Polyfill -->
    <script src="assets/vendor/object-fit-images/dist/ofi.min.js"></script>

    <!-- GSAP -->
    <script src="assets/vendor/gsap/src/minified/TweenMax.min.js"></script>
    <script src="assets/vendor/gsap/src/minified/plugins/ScrollToPlugin.min.js"></script>

    <!-- Popper -->
    <script src="assets/vendor/popper.js/dist/umd/popper.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Sticky Kit -->
    <script src="assets/vendor/sticky-kit/dist/sticky-kit.min.js"></script>

    <!-- Jarallax -->
    <script src="assets/vendor/jarallax/dist/jarallax.min.js"></script>
    <script src="assets/vendor/jarallax/dist/jarallax-video.min.js"></script>

    <!-- imagesLoaded -->
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>

    <!-- Flickity -->
    <script src="assets/vendor/flickity/dist/flickity.pkgd.min.js"></script>

    <!-- Photoswipe -->
    <script src="assets/vendor/photoswipe/dist/photoswipe.min.js"></script>
    <script src="assets/vendor/photoswipe/dist/photoswipe-ui-default.min.js"></script>

    <!-- Jquery Validation -->
    <script src="assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>

    <!-- Jquery Countdown + Moment -->
    <script src="assets/vendor/jquery-countdown/dist/jquery.countdown.min.js"></script>
    <script src="assets/vendor/moment/min/moment.min.js"></script>
    <script src="assets/vendor/moment-timezone/builds/moment-timezone-with-data.min.js"></script>

    <!-- Hammer.js -->
    <script src="assets/vendor/hammerjs/hammer.min.js"></script>

    <!-- NanoSroller -->
    <script src="assets/vendor/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>

    <!-- SoundManager2 -->
    <script src="assets/vendor/soundmanager2/script/soundmanager2-nodebug-jsmin.js"></script>

    <!-- Seiyria Bootstrap Slider -->
    <script src="assets/vendor/bootstrap-slider/dist/bootstrap-slider.min.js"></script>

    <!-- Summernote -->
    <script src="assets/vendor/summernote/dist/summernote-bs4.min.js"></script>

    <!-- nK Share -->
    <script src="assets/plugins/nk-share/nk-share.js"></script>

    <!-- GoodGames -->
    <script src="assets/js/goodgames.min.js"></script>
    <script src="assets/js/goodgames-init.js"></script>
    <!-- END: Scripts -->



</body>

</html>