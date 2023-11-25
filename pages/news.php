<?php
session_start();
include_once('../mod/database_connection.php');

$id = $_GET['id'];
$user_id = (isset($_SESSION['id_account'])) ? $_SESSION['id_account'] : 0;


$sql = "SELECT * FROM news n, news_type nt WHERE n.news_type_id = nt.id AND n.id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$news_type_name = $row["news_type_name"];
$news_type_id = $row["news_type_id"];

?>


<!DOCTYPE html>
<html lang="en">

<?php include "../mod/head.php"; ?>

<body>

    <?php include "../mod/nav.php"; ?>

    <?php

    if (isset($_POST['delete_id']) && $_POST['delete_id'] != "") {
        $id_comment = $_POST['delete_id'];

        $sql = "DELETE FROM `news_comments` WHERE id = ? OR reply_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement");
        }
        $stmt->bind_param("ii", $id_comment, $id_comment);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        echo '<script>
    window.addEventListener("load", function() {
    notification_dialog("Success", "Delete Successful!!");
    $("html, body").animate({
        scrollTop: $("#frm_post_comment").offset().top 
      }, 1000);
    });

    </script>';
    } else {
        $comment = (isset($_POST['comment'])) ? $_POST['comment'] : '';
        if (isset($_POST['comment']) && isset($_SESSION['id_account'])) {

            if ($comment == '') {
                echo '<script>
            window.addEventListener("load", function() {
            notification_dialog("Failed", "Enter comment!!!");
            $("html, body").animate({
                scrollTop: $("#frm_post_comment").offset().top 
              }, 1000);
            });
            </script>';

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

                    echo '<script>
                window.addEventListener("load", function() {
                notification_dialog("Success", "Edit Successful!!");
                $("html, body").animate({
                    scrollTop: $("#frm_post_comment").offset().top 
                  }, 1000);
                });
                </script>';

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
                    echo '<script>
                window.addEventListener("load", function() {
                notification_dialog("Success", "Comment Successful!!");
                $("html, body").animate({
                    scrollTop: $("#frm_post_comment").offset().top 
                  }, 1000);
                });
                </script>';
                }

            }
        }
        if (isset($_POST['comment']) && !isset($_SESSION['id_account'])) {
            echo '<script>
            window.addEventListener("load", function() {
            notification_dialog("Failed", "Please log in before commenting!!!");
            $("html, body").animate({
                scrollTop: $("#frm_post_comment").offset().top 
              }, 1000);
            });
            </script>';
        }

    }

    ?>

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
                                                <a style="font-size: 0.7em;"
                                                    href="javascript:delete_comments(<?= $row_news_comments['id'] ?>)"
                                                    class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Delete</a>
                                                <a style="font-size: 0.7em;"
                                                    href="javascript:edit_comment(<?= $row_news_comments['id'] ?>, '<?= $row_news_comments['comment'] ?>')"
                                                    class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Edit</a>
                                            <?php } else { ?>
                                                <a style="font-size: 0.7em;"
                                                    href="javascript:reply(<?= $row_news_comments['id'] ?>, '<?= $row_news_comments['full_name'] ?>')"
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
                                                        <a style="font-size: 0.7em;"
                                                            href="javascript:delete_comments(<?= $row_news_comments_reply['id'] ?>)"
                                                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Delete</a>
                                                        <a style="font-size: 0.7em;"
                                                            href="javascript:edit_comment(<?= $row_news_comments_reply['id'] ?>, '<?= $row_news_comments_reply['comment'] ?>')"
                                                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">Edit</a>
                                                    <?php } else { ?>
                                                        <a style="font-size: 0.7em;"
                                                            href="javascript:reply(<?= $row_news_comments['id'] ?>, '<?= $row_news_comments_reply['full_name'] ?>')"
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

                        <!-- START: Reply -->
                        <div class="nk-gap-2"></div>
                        <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Leave</span> a Reply</span></h3>
                        <div class="nk-gap"></div>
                        <div class="nk-reply">
                            <form id="frm_post_comment" name="frm_post_comment" method="post" class="nk-form"
                                novalidate="novalidate">
                                <div class="row vertical-gap sm-gap">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control required" value="" name="reply_id"
                                            hidden>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control required" value="" name="edit_id" hidden>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control required" value="" name="delete_id"
                                            hidden>
                                    </div>
                                </div>
                                <textarea class="form-control required" required name="comment" rows="5"
                                    placeholder="Comment *" aria-required="true"></textarea>
                                <div class="nk-gap-1"></div>
                                <button type="submit" class="nk-btn nk-btn-rounded nk-btn-color-main-1">Post
                                    Comment</button>
                                <button type="button" onclick="cancel_reply()"
                                    class="nk-btn nk-btn-rounded nk-btn-color-main-1">Cancel</button>
                            </form>

                            <script>
                                function reply(reply_id, user) {
                                    document.frm_post_comment.comment.value = "";
                                    document.frm_post_comment.comment.placeholder = "Reply " + user;
                                    document.frm_post_comment.reply_id.value = reply_id;
                                    document.frm_post_comment.comment.focus();
                                }

                                function delete_comments(id) {
                                    document.frm_post_comment.delete_id.value = id;
                                    document.frm_post_comment.submit();
                                }

                                function edit_comment(edit_id, comment) {
                                    document.frm_post_comment.comment.placeholder = "Edit comment";
                                    document.frm_post_comment.comment.value = comment;
                                    document.frm_post_comment.edit_id.value = edit_id;
                                    document.frm_post_comment.comment.focus();
                                }

                                function cancel_reply() {
                                    document.frm_post_comment.comment.value = "";
                                    document.frm_post_comment.comment.placeholder = "Comment *";
                                    document.frm_post_comment.edit_id.value = "";
                                    document.frm_post_comment.reply_id.value = "";
                                }
                            </script>


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


    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->



</body>

</html>