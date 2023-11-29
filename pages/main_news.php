<?php
session_start();
include_once('../mod/database_connection.php');
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

                <li><a href="../Galaxy_Game_Store/main_news">News</a></li>

                <li><span class="fa fa-angle-right"></span></li>
                <div class="nk-gap-1"></div>
                <li><span>Main News Categories</span></li>

            </ul>
        </div>
        <!-- END: Breadcrumbs -->

        <div class="container">

            <div class="nk-gap-2"></div>

            <!-- START: News CATEGORY List -->
            <ul class="nk-forum">
                <?php
                $sql_news_category = "SELECT nt.id, nt.news_type_name,  
                MIN(n.publish_date) as start_post,
                MAX(n.publish_date) as last_post,
                COUNT(n.id) as total_post
                FROM news_type nt
                LEFT JOIN news n ON nt.id = n.news_type_id  
                GROUP BY nt.id";

                $result_news_category = $conn->query($sql_news_category);
                while ($row = $result_news_category->fetch_assoc()) { ?>
                    <li>
                        <div class="nk-forum-icon">
                            <?php if ($row['id'] == 1) { ?>
                                <span class="ion-flame"></span>
                            <?php } ?>
                            <?php if ($row['id'] == 2) { ?>
                                <span class="ion-steam"></span>
                            <?php } ?>
                            <?php if ($row['id'] == 3) { ?>
                                <span class="ion-help-buoy"></span>
                            <?php } ?>
                            <?php if ($row['id'] == 4) { ?>
                                <span class="ion-chatboxes"></span>
                            <?php } ?>
                            <?php if ($row['id'] == 5) { ?>
                                <span class="ion-ios-game-controller-b"></span>
                            <?php } else {
                                echo "";
                            } ?>
                        </div>
                        <div class="nk-forum-title">
                            <h3><a href="./category_news?id=<?= $row['id'] ?>">
                                    <?= $row['news_type_name'] ?>
                                </a></h3>
                            <div class="nk-forum-title-sub">Started post on
                                <?= date('M d, Y', strtotime($row['start_post'])) ?>
                            </div>
                        </div>
                        <div class="nk-forum-count">
                            <?= $row['total_post'] ?> <br> posts
                        </div>

                        <div class="nk-forum-activity">
                            <div class="nk-forum-activity-date">
                                Latest post on
                                <?= date('M d, Y', strtotime($row['last_post'])) ?>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <!-- END: News CATEGORY List -->

            <div class="nk-gap-2"></div>

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