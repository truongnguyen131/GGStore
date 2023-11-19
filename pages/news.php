<?php
session_start();
include_once('../mod/database_connection.php');

$id = $_GET['id'];
$user_id = $_SESSION['id_account'];

$comment = (isset($_POST['comment'])) ? $_POST['comment'] : '';

if (isset($_POST['comment'])) {

    if ($comment == '') {
        echo 'Enter comment!!!';
    } else {
        if ($_POST['edit_id'] != "") {
            $edit_id = $_POST['edit_id'];
            $sql = "UPDATE `news_comments` SET `comment`= ?,`comment_date`= NOW() WHERE `id` = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing statement");
            }
            $stmt->bind_param("si", $comment, $edit_id);
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            echo 'Edit Successful!!';
        } else {
            $comment_date = date('Y-m-d H:i:s');
            if ($_POST['reply_id'] == "") {
                $reply_id = null;
            } else {
                $reply_id = $_POST['reply_id'];
            }


            $sql = "INSERT INTO `news_comments`(`comment`, `comment_date`, `news_id`, `user_id`, `reply_id`) 
            VALUES (?,NOW(),?,?,?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing statement");
            }
            $stmt->bind_param("siii", $comment, $id, $user_id, $reply_id);
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            echo 'Comment Successful!!';
        }

    }
}



if (isset($_GET['delete'])) {
    $id_comment = $_GET['delete'];

    $sql = "DELETE FROM `news_comments` WHERE id = ? OR reply_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement");
    }
    $stmt->bind_param("ii", $id_comment, $id_comment);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    echo 'Delete Successful!!';
}


$sql = "SELECT * FROM news n, news_type nt WHERE n.news_type_id = nt.id AND n.id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$news_type_name = $row["news_type_name"];
$news_type_id = $row["news_type_id"];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>GoodGames | Grab your sword and fight the Horde</title>

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




                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Top Contacts -->


        <nav class="nk-navbar nk-navbar-top nk-navbar-sticky nk-navbar-autohide">
            <div class="container">
                <div class="nk-nav-table">

                    <a href="index.html" class="nk-nav-logo">
                        <img src="assets/images/logo1.png" alt="GoodGames" width="199">
                    </a>

                    <ul class="nk-nav nk-nav-right d-none d-lg-table-cell" data-nav-mobile="#nk-nav-mobile">

                        <li class=" nk-drop-item">
                            <a href="elements.html">
                                Features

                            </a>
                            <ul class="dropdown">

                                <li>
                                    <a href="elements.html">
                                        Elements (Shortcodes)

                                    </a>
                                </li>
                                <li class=" nk-drop-item">
                                    <a href="forum.html">
                                        Forum

                                    </a>
                                    <ul class="dropdown">

                                        <li>
                                            <a href="forum.html">
                                                Forum

                                            </a>
                                        </li>
                                        <li>
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
                        <li class="active nk-drop-item">
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
                                <li class="active">
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

                <li><a href="./category_news?id=<?= $row["news_type_id"] ?>">
                        <?= $news_type_name ?>
                    </a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <li><a href="../Galaxy_Game_Store/news?id=<?= $row['id'] ?>">
                        <?= substr($row['title'], 0, 30) . (strlen($row['title']) > 30 ? '...' : '') ?>
                    </a></li>

                <li><span class="fa fa-angle-right"></span></li>

                <div class="nk-gap-1"></div>


            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">
            <div class="row vertical-gap">

                <div class="col-lg-8">
                    <!-- START: Post -->
                    <div class="nk-blog-post nk-blog-post-single">
                        <!-- START: Post Text -->
                        <div class="nk-post-text mt-0">
                            <div class="nk-gap-1"></div>
                            <h1 class="nk-post-title h2">
                                <?= $row["title"] ?>
                            </h1>
                            <div class="nk-gap"></div>
                            <div class="nk-post-categories">
                                <span class="bg-main-1">
                                    <?= $row["news_type_name"] ?>
                                </span>
                            </div>

                            <div class="nk-gap-2"></div>

                            <blockquote class="nk-blockquote">
                                <div class="nk-blockquote-icon"><span>"</span></div>
                                <div class="nk-blockquote-content">
                                    <?= $row["header"] ?>
                                </div>
                                <div class="nk-blockquote-author">
                                    <span>
                                        Post on
                                        <?= date('M d, Y', strtotime($row['publish_date'])) ?>
                                    </span>
                                </div>
                            </blockquote>

                            <div class="nk-gap"></div>

                            <?php
                            $sql_news_contents = "SELECT * FROM `news_content` WHERE news_id = $id ";
                            $result_news_contents = $conn->query($sql_news_contents);
                            while ($row_news_contents = $result_news_contents->fetch_assoc()) { ?>
                                <?php if ($row_news_contents['image'] != "") { ?>
                                    <img class="float-left mt-0" width="100%" src="./uploads/<?= $row_news_contents['image'] ?>"
                                        alt="<?= $row_news_contents['title'] ?>">
                                <?php } ?>
                                <h3 class="h4">
                                    <?= $row_news_contents['title'] ?>
                                </h3>

                                <p>
                                    <?= $row_news_contents['content'] ?>
                                </p>

                                <?php if ($row_news_contents['video'] != "") { ?>
                                    <video autoplay controls muted width="100%">
                                        <source src="./uploads/<?= $row_news_contents['video'] ?>" type="video/mp4">
                                    </video>
                                <?php } ?>

                                <div class="nk-gap"></div>
                            <?php } ?>

                            <p>
                                <?= $row['footer'] ?>
                            </p>

                            <div class="nk-gap"></div>
                        </div>
                        <!-- END: Post Text -->

                        <!-- START: Comments -->
                        <?php
                        $sql_news_comments = "SELECT nc.*, u.full_name FROM news_comments nc, users u 
                        WHERE u.id = nc.user_id AND reply_id is NULL AND news_id = $id";
                        $result_news_comments = $conn->query($sql_news_comments);
                        if ($result_news_comments->num_rows > 0) { ?>

                            <div id="comments"></div>
                            <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Post</span> Comments</span></h3>
                            <div class="nk-gap"></div>
                            <div class="nk-comments">
                                <?php while ($row_news_comments = $result_news_comments->fetch_assoc()) { ?>

                                    <div class="nk-comment">
                                        <div class="nk-comment-meta">
                                            <i class="fas fa-user"></i> by <a href="#">
                                                <?= $row_news_comments['full_name'] ?>
                                            </a> in
                                            <a href="#">
                                                <?= $row_news_comments['comment_date'] ?>
                                            </a>
                                            <?php if ($row_news_comments['user_id'] == $user_id) { ?>
                                                <a href="javascript:delete_comments(<?= $row_news_comments['id'] ?>)"
                                                    class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Delete</a>
                                                <a href="javascript:edit_comment(<?= $row_news_comments['id'] ?>, '<?= $row_news_comments['comment'] ?>')"
                                                    class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Edit</a>
                                            <?php } else { ?>
                                                <a href="javascript:reply(<?= $row_news_comments['id'] ?>, '<?= $row_news_comments['full_name'] ?>')"
                                                    class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Reply</a>
                                            <?php } ?>
                                        </div>
                                        <div class="nk-comment-text">
                                            <p>
                                                <?= $row_news_comments['comment'] ?>
                                            </p>
                                        </div>

                                        <?php
                                        $reply_id = $row_news_comments['id'];
                                        $sql_news_comments_reply = "SELECT nc.*, u.full_name FROM news_comments nc, users u 
                                        WHERE u.id = nc.user_id AND reply_id = $reply_id AND news_id = $id";
                                        $result_news_comments_reply = $conn->query($sql_news_comments_reply);
                                        while ($row_news_comments_reply = $result_news_comments_reply->fetch_assoc()) { ?>
                                            <div class="nk-comment">
                                                <div class="nk-comment-meta">
                                                    <i class="fas fa-user"></i> by <a href="#">
                                                        <?= $row_news_comments_reply['full_name'] ?>
                                                    </a> in
                                                    <a href="#">
                                                        <?= $row_news_comments_reply['comment_date'] ?>
                                                    </a>
                                                    <?php if ($row_news_comments_reply['user_id'] == $user_id) { ?>
                                                        <a href="javascript:delete_comments(<?= $row_news_comments_reply['id'] ?>)"
                                                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Delete</a>
                                                        <a href="javascript:edit_comment(<?= $row_news_comments_reply['id'] ?>, '<?= $row_news_comments_reply['comment'] ?>')"
                                                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Edit</a>
                                                    <?php } else { ?>
                                                        <a href="javascript:reply(<?= $row_news_comments['id'] ?>, '<?= $row_news_comments_reply['full_name'] ?>')"
                                                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Reply</a>
                                                    <?php } ?>

                                                </div>
                                                <div class="nk-comment-text">
                                                    <p>
                                                        <?= $row_news_comments_reply['comment'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                <?php } ?>

                            </div>

                        <?php } ?>
                        <!-- END: Comments -->

                        <script>
                            function reply(reply_id, user) {
                                document.frm_post_comment.comment.value = "";
                                document.frm_post_comment.comment.placeholder = "Reply " + user;
                                document.frm_post_comment.reply_id.value = reply_id;
                                document.frm_post_comment.comment.focus();
                            }

                            function delete_comments(id) {
                                location.href = '../Galaxy_Game_Store/news?id=<?= $id ?>&delete=' + id;
                            }

                            function edit_comment(edit_id, comment) {
                                document.frm_post_comment.comment.value = comment;
                                document.frm_post_comment.edit_id.value = edit_id;
                                document.frm_post_comment.comment.focus();
                            }
                        </script>

                        <!-- START: Reply -->
                        <div class="nk-gap-2"></div>
                        <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Leave</span> a Reply</span></h3>
                        <div class="nk-gap"></div>
                        <div class="nk-reply">
                            <form name="frm_post_comment" method="post" class="nk-form" novalidate="novalidate">
                                <div class="row vertical-gap sm-gap">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control required" value="" name="reply_id"
                                            hidden>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control required" value="" name="edit_id" hidden>
                                    </div>
                                </div>
                                <textarea class="form-control required" required name="comment" rows="5"
                                    placeholder="Comment *" aria-required="true"></textarea>
                                <div class="nk-gap-1"></div>
                                <button type="submit" class="nk-btn nk-btn-rounded nk-btn-color-main-1">Post
                                    Comment</button>
                            </form>

                        </div>
                        <!-- END: Reply -->
                    </div>
                    <!-- END: Post -->
                </div>

                <div class="col-lg-4">

                    <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">

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
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">Latest</span> Video</span></h4>
                            <div class="nk-widget-content">
                                <div class="nk-plain-video" data-video="https://www.youtube.com/watch?v=vXy8UBazlO8">
                                </div>
                            </div>
                        </div>
                        <div class="nk-widget nk-widget-highlighted">
                            <h4 class="nk-widget-title"><span><span class="text-main-1">SIMILAR</span> ARTICLES</span>
                            </h4>
                            <style>
                                .popular_name {
                                    max-width: 700px;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                }
                            </style>
                            <div class="nk-widget-content">
                                <?php
                                $sql_SIMILAR = "SELECT *,(SELECT image FROM news_content WHERE news_id = n.id AND image != '' LIMIT 1) AS image
                                 FROM `news` n WHERE news_type_id = $news_type_id LIMIT 3";
                                $result_SIMILAR = $conn->query($sql_SIMILAR);
                                while ($row_SIMILAR = $result_SIMILAR->fetch_assoc()) { ?>
                                    <div class="nk-widget-post">
                                        <a href="../Galaxy_Game_Store/news?id=<?= $row_SIMILAR['id'] ?>"
                                            title="<?= $row_SIMILAR['title'] ?>" class="nk-post-image">
                                            <img src="./uploads/<?= $row_SIMILAR['image'] ?>"
                                                alt="<?= $row_SIMILAR['title'] ?>">
                                        </a>
                                        <h5 class="nk-post-title popular_name">
                                            <a href="../Galaxy_Game_Store/news?id=<?= $row_SIMILAR['id'] ?>"
                                                title="<?= $row_SIMILAR['title'] ?>">
                                                <?= $row_SIMILAR['title'] ?>
                                            </a>
                                        </h5>
                                        <div class="nk-post-date"><span class="fa fa-calendar"></span>
                                            <?= date('M d, Y', strtotime($row_SIMILAR['publish_date'])) ?>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        </div>

                    </aside>

                </div>

            </div>
        </div>

        <div class="nk-gap-2"></div>



        <!-- START: Footer -->
        <?php include "../mod/footer.php"; ?>
        <!-- END: Footer -->


    </div>







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