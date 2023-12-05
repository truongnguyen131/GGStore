<header class="nk-header nk-header-opaque">

    <style>
        .item_suggest .type_suggest_ver1 {
            width: 30%;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .item_suggest .name_suggest_ver1 {
            width: 70%;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .item_suggest .name_suggest_ver1 span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }



        .swal2-styled.swal2-confirm {
            background-color: #dd163b !important;
        }
    </style>

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
                    <li><a class="nk-social-twitter" href="#" target="_blank"><span class="fab fa-twitter"></span></a>
                    </li>
                    <li><a class="nk-social-pinterest" href="#"><span class="fab fa-pinterest-p"></span></a></li>


                </ul>
            </div>
            <div class="nk-contacts-right">
                <ul class="nk-contacts-icons">

                    <script>
                        function add_to_cart(id) {
                            $.post('../Galaxy_Game_Store/pages/add_to_cart.php', { product_id: id }, function (data) {
                                $('#product_count').html(data);
                            });
                        }
                        function by_now(id) {
                            $.post('../Galaxy_Game_Store/pages/add_to_cart.php?buy_now', { product_id: id }, function (data) {
                                $('#product_count').html(data);
                            });
                        }
                        function add_to_cart(id, type) {
                            $.post('../Galaxy_Game_Store/pages/add_to_cart.php', { product_id: id, type: type }, function (data) {
                                $('#product_count').html(data);
                            });
                        }
                        function by_now(id, type) {
                            $.post('../Galaxy_Game_Store/pages/add_to_cart.php?buy_now', { product_id: id, type: type }, function (data) {
                                $('#product_count').html(data);
                            });
                        }

                    </script>

                    <li>
                        <a href="#" id="dialog_notification" data-toggle="modal" style="display: none;"
                            data-target="#modalSearch">a
                        </a>
                    </li>

                    <li class="search_icon icon" id="search_icon">
                        <a href="#">
                            <span class="fa fa-search"></span>
                        </a>
                        <div class="search_box" id="search_box">
                            <form action="" method="post">
                                <select name="option_search" id="search_type" onchange="searchType(this.value)">
                                    <option value="all">All</option>
                                    <option value="game">Game</option>
                                    <option value="gear">Gear</option>
                                    <option value="genre">Category</option>
                                    <option value="news">News</option>
                                </select>
                                <input type="text" name="search_textbox" id="search_textbox" placeholder="Search"
                                    onkeyup="searchInput(this.value)">
                            </form>
                            <div class="suggest_search" id="suggest_search">
                            </div>
                        </div>
                        <script>
                            const user_icon = document.getElementById('user_icon');
                            const user_box = document.getElementById('user_box');
                            const search_icon = document.getElementById('search_icon');
                            const search_box = document.getElementById('search_box');

                            search_icon.onclick = function (event) {
                                event.stopPropagation();
                                search_box.style.display = 'flex';
                            };

                            document.addEventListener('click', function (event) {
                                const isClickInside = search_box.contains(event.target);
                                if (search_box.style.display === 'flex' && !isClickInside) {
                                    search_box.style.display = 'none';
                                }
                            });
                        </script>
                    </li>

                    <script>
                        function searchInput(str) {
                            var searchTypeValue = document.getElementById("search_type").value;
                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function () {
                                if (this.readyState == 4 && this.status == 200) {
                                    document.getElementById("suggest_search").innerHTML = this.responseText;
                                }
                            };
                            xmlhttp.open("GET", "./mod/search.php?search=" + str + "&type=" + searchTypeValue, true);
                            xmlhttp.send();
                        }

                        function searchType(str) {
                            var searchInputValue = document.getElementById("search_textbox").value;
                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function () {
                                if (this.readyState == 4 && this.status == 200) {
                                    document.getElementById("suggest_search").innerHTML = this.responseText;
                                }
                            };
                            xmlhttp.open("GET", "./mod/search.php?search=" + searchInputValue + "&type=" + str, true);
                            xmlhttp.send();
                        }
                    </script>

                    <li>
                        <a href="../Galaxy_Game_Store/shopping_cart">
                            <span>
                                <span class="fa fa-shopping-cart"></span>
                                <span class="nk-badge" id="product_count">
                                    <?php
                                    $total_quantity = 0;
                                    if (isset($_SESSION["shopping_cart"])) {
                                        $total_quantity = array_sum(array_column($_SESSION['shopping_cart'], 'quantity'));
                                    }
                                    echo $total_quantity;
                                    ?>
                                </span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <?php

                        if (isset($_SESSION["userName"]) && $_SESSION['userName']) { ?>
                            <span class="nk-cart-toggle">
                                <span class="fa fa-user"></span>
                                <div class="name_and_money">
                                    <span>
                                        <?= $_SESSION["userName"] ?>
                                    </span>
                                    <span style="margin-left: 20px;">
                                        <?php
                                        $id_account = $_SESSION["id_account"];
                                        $sql_dID = "SELECT Gcoin FROM users WHERE id = $id_account";
                                        $result_dID = $conn->query($sql_dID);
                                        $row_dID = $result_dID->fetch_assoc();
                                        echo $row_dID['Gcoin'];
                                        ?>
                                        <i class="fas fa-gem"></i>
                                    </span>
                                </div>
                            </span>
                        <?php } else { ?>
                            <a href="./pages/login.php">
                                <span class="fa fa-user"></span>
                            </a>
                        <?php } ?>

                        <div class="nk-cart-dropdown" style="width: 80%;">
                            <div class="text-center">

                                <?php
                                if ($_SESSION["role"] == "developer") { ?>
                                    <a style="width: 100%;" href="./admin/pages/template/dashboard.php"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                        GGS Admin</a>
                                <?php } else { ?>
                                    <a style="width: 100%;" href="../Galaxy_Game_Store/profile"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                        Profile</a>
                                    <div class="nk-gap"></div>
                                    <a style="width: 100%;" href="../Galaxy_Game_Store/bag"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                        My bag</a>
                                    <div class="nk-gap"></div>
                                    <a style="width: 100%;" href="../Galaxy_Game_Store/orders"
                                        class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                        Orders</a>
                                <?php }
                                ?>
                            </div>
                            <div class="nk-gap"></div>
                            <div class="text-center">
                            </div>
                            <div class="text-center">
                                <a style="width: 100%;" href="./pages/logout.php"
                                    class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                    Logout</a>
                            </div>
                        </div>
                    </li>



                </ul>
            </div>
        </div>
    </div>
    <!-- END: Top Contacts -->

    <!-- START: Navbar -->
    <nav class="nk-navbar nk-navbar-top nk-navbar-sticky nk-navbar-autohide">
        <div class="container">
            <div class="nk-nav-table">

                <a href="../Galaxy_Game_Store/home" class="nk-nav-logo">
                    <img src="assets/images/logo1.png" alt="GoodGames" width="220">
                </a>

                <ul class="nk-nav nk-nav-right d-none d-lg-table-cell" data-nav-mobile="#nk-nav-mobile">

                    <li>
                        <a href="../Galaxy_Game_Store/store">
                            Store
                        </a>
                    </li>

                    <li class="nk-drop-item" id="genre_item">
                        <a>
                            CATEGORY
                        </a>
                        <div class="dropdown_menu" id="genre_dropdown"
                            style="margin-top: 38.7656px; margin-left: -9px;">
                            <?php
                            $sql_genres = 'SELECT * FROM genres';
                            $result_genres = $conn->query($sql_genres);
                            $count = 0;

                            while ($row_genres = mysqli_fetch_array($result_genres)) {
                                if ($count % 5 == 0) {

                                    if ($count > 0) {
                                        echo '</ul>';
                                    }
                                    echo '<ul>';
                                }
                                ?>
                        <li>
                            <a href="../Galaxy_Game_Store/store?id_category=<?= $row_genres['id'] ?>">
                                <?= $row_genres['genre_name'] ?>
                            </a>
                        </li>
                        <?php
                        $count++;
                            }
                            if ($count % 5 != 0) {
                                echo '</ul>';
                            }
                            ?>
            </div>

            </li>

            <li>
                <a href="../Galaxy_Game_Store/main_news">
                    NEWS
                </a>
            </li>
            <li>
                <a href="../Galaxy_Game_Store/recharge">
                    Recharge
                </a>
            </li>
            <li>
                <a href="#Contact">
                    CONTACT US
                </a>
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

<!-- START: Navbar Mobile -->
<div id="nk-nav-mobile" class="nk-navbar nk-navbar-side nk-navbar-right-side nk-navbar-overlay-content d-lg-none">
    <div class="nano">
        <div class="nano-content">
            <a href="index.html" class="nk-nav-logo">
                <img src="assets/images/logo1.png" alt="" width="120">
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

<!-- START: Dialog Notification -->
<div class="nk-modal modal fade" id="modalSearch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body" style="padding: 20px;">
                <div id="div_status" style="margin-bottom: 0;" class="nk-info-box ">
                    <div class="nk-info-box-icon">
                        <i id="i_status" class=""></i>
                    </div>
                    <h3 id="status"></h3>
                    <em id="message"></em>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Dialog Notification -->

<script>
    function notification_dialog(status, message) {
        document.getElementById("status").innerHTML = status;
        document.getElementById("message").innerHTML = message;

        if (status == "Success") {
            if (document.getElementById("div_status").classList.contains("text-danger")) {
                document.getElementById("div_status").classList.remove("text-danger");
            }
            document.getElementById("div_status").classList.add("text-success");

            if (document.getElementById("i_status").classList.contains("ion-close-round")) {
                document.getElementById("i_status").classList.remove("ion-close-round");
            }
            document.getElementById("i_status").classList.add("ion-checkmark-round");
        } else {
            if (document.getElementById("div_status").classList.contains("text-success")) {
                document.getElementById("div_status").classList.remove("text-success");
            }
            document.getElementById("div_status").classList.add("text-danger");

            if (document.getElementById("i_status").classList.contains("ion-checkmark-round")) {
                document.getElementById("i_status").classList.remove("ion-checkmark-round");
            }
            document.getElementById("i_status").classList.add("ion-close-round");
        }


        document.getElementById("dialog_notification").click();
    }

</script>