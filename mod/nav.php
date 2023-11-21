<script>
    function searchInput(str) {
        var searchTypeValue = document.getElementById("search_type").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("suggest_search").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "../mod/search.php?search=" + str + "&type=" + searchTypeValue, true);
        xmlhttp.send();
    }

    function searchType(str) {
        var searchInputValue = document.getElementById("search_textbox").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("suggest_search").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "../mod/search.php?search=" + searchInputValue + "&type=" + str, true);
        xmlhttp.send();
    }
</script>
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
                    <li><a class="nk-social-twitter" href="#" target="_blank"><span class="fab fa-twitter"></span></a>
                    </li>
                    <li><a class="nk-social-pinterest" href="#"><span class="fab fa-pinterest-p"></span></a></li>


                </ul>
            </div>
            <div class="nk-contacts-right">
                <ul class="nk-contacts-icons">

                    <li>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:add_to_cart(<?= 2 ?>)" class="nk-btn nk-btn-rounded nk-btn-color-main-1 w-100">Add to card</a>
                        </div>

                        <script>
                            function add_to_cart(id) {
                                $.post('../Galaxy_Game_Store/pages/add_to_cart.php', {
                                    product_id: id
                                }, function(data) {
                                    $('#product_count').html(data);
                                });
                            }
                        </script>
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
                                    <option value="category">Category</option>
                                    <option value="news">News</option>
                                </select>
                                <input type="text" name="search_textbox" id="search_textbox" placeholder="Search" onkeyup="searchInput(this.value)">
                            </form>
                            <div class="suggest_search" id="suggest_search">
                            </div>
                        </div>

                    </li>

                    <li>
                        <?php
                        if (isset($_SESSION["userName"]) && $_SESSION['userName']) { ?>
                            <span class="nk-cart-toggle">
                                <?= $_SESSION["userName"] ?> <span class="fa fa-user"></span>
                            </span>
                        <?php } else { ?>
                            <a href="../Galaxy_Game_Store/login">
                                <span class="fa fa-user"></span>
                            </a>
                        <?php } ?>

                        <div class="nk-cart-dropdown" style="width: 60%;">

                            <div class="text-center">

                                <a href="" class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                    Infomation</a>

                                <div class="nk-gap"></div>
                                <?php
                                if ($_SESSION["role"] == "developer") { ?>
                                    <a href="./admin/pages/template/dashboard.php" class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                        GGS Admin</a>
                                <?php } else { ?>
                                    <a href="" class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                        My bag</a>
                                <?php }
                                ?>


                            </div>
                            <div class="nk-gap"></div>
                            <div class="text-center">

                            </div>

                            <div class="text-center">
                                <a href="./pages/logout.php" class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">
                                    Logout</a>
                            </div>
                        </div>

                    </li>

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
                        <a href="">
                            Store
                        </a>
                    </li>
                    <li class="nk-drop-item">
                        <a href="">
                            CATEGORY
                        </a>
                        <ul class="dropdown" style="margin-top: 38.7656px; display: none; opacity: 0; margin-left: -9px;">

                            <li>
                                <a href="">
                                    Tournament

                                </a>
                            </li>
                            <li>
                                <a href="">
                                    Teams

                                </a>
                            </li>
                            <li>
                                <a href="">
                                    Teammate

                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="../Galaxy_Game_Store/main_news">
                            NEWS
                        </a>
                    </li>
                    <li>
                        <a href="">
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

        document.getElementById("div_status").classList.add("text-success");
        document.getElementById("i_status").classList.add("ion-checkmark-round");
        if (status != "Success") {
            document.getElementById("div_status").classList.add("text-danger");
            document.getElementById("i_status").classList.add("ion-close-round");
        }

        document.getElementById("dialog_notification").click();
    }
</script>