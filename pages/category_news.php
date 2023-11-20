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



    <!-- START: Scripts -->
    <?php include "../mod/add_script.php"; ?>
    <!-- END: Scripts -->



</body>

</html>