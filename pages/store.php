<?php
session_start();
include_once('./mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include "./mod/head.php"; ?>
<link rel="stylesheet" href="./assets/css/grand_custom.css">
<link rel="stylesheet" href="./assets/css/new.css">

<body>
    <?php include "./mod/nav.php"; ?>

    <div class="nk-main">
        <!-- START: Breadcrumbs -->
        <div class="nk-gap-1"></div>
        <div class="container">
            <ul class="nk-breadcrumbs">
                <li><a href="index.html">Home</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><a href="store.html">Store</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><span>Action Games</span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">
            <!-- START: Categories -->
            <div class="row vertical-gap">
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="assets/images/icon-mouse.png" alt="">
                        </div>
                        <div class="nk-feature-cont">
                            <h3 class="nk-feature-title"><a href="#">PC</a></h3>
                            <h3 class="nk-feature-title text-main-1"><a href="#">View Games</a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="assets/images/icon-gamepad.png" alt="">
                        </div>
                        <div class="nk-feature-cont">
                            <h3 class="nk-feature-title"><a href="#">PS4</a></h3>
                            <h3 class="nk-feature-title text-main-1"><a href="#">View Games</a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="nk-feature-1">
                        <div class="nk-feature-icon">
                            <img src="assets/images/icon-gamepad-2.png" alt="">
                        </div>
                        <div class="nk-feature-cont">
                            <h3 class="nk-feature-title"><a href="#">Xbox</a></h3>
                            <h3 class="nk-feature-title text-main-1"><a href="#">View Games</a></h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Categories -->

            <!-- START: Products Filter -->
            <div class="nk-gap-2"></div>
            <div class="row vertical-gap">
                <div class="col-lg-12">
                    <div class="row vertical-gap">
                        <div class="col-md-3">
                            <select class="form-control" onchange="filter()">
                                <option value="all">All Classify</option>
                                <option value="game">Game</option>
                                <option value="gear">Gear</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="select_genre" id="select_genre">
                                <span>Select Genres</span>
                                <ion-icon name="chevron-down-outline"></ion-icon>
                            </div>
                            <div class="list_genre" id="list_genre">
                                <?php
                                $list_genres = "SELECT id, genre_name, CASE WHEN genre_name LIKE '%gaming%' THEN 'Gear' ELSE 'Game' END AS type FROM genres";
                                $result_genres = mysqli_query($conn, $list_genres);

                                // Khởi tạo mảng để lưu trữ các phần tử theo type
                                $genre_groups = array();

                                while ($genre_item = mysqli_fetch_array($result_genres)) {
                                    $type = $genre_item['type'];
                                    $genre_name = $genre_item['genre_name'];

                                    // Kiểm tra xem type đã tồn tại trong mảng hay chưa
                                    if (!isset($genre_groups[$type])) {
                                        // Nếu chưa tồn tại, khởi tạo mảng cho type đó
                                        $genre_groups[$type] = array();
                                    }

                                    // Thêm phần tử vào mảng của type tương ứng
                                    $genre_groups[$type][] = $genre_item;
                                }

                                // Hiển thị danh sách theo type
                                foreach ($genre_groups as $type => $genres) {
                                    echo '<span class="type_genre">' . $type . '</span>';
                                    echo '<div>';
                                    foreach ($genres as $genre) {
                                        echo '<label for="' . $genre['id'] . '" class="genre_item" onchange="filter()">';
                                        echo '<input type="checkbox" name="" id="' . $genre['id'] . '" value="' . $genre['id'] . '">';
                                        echo '<span>' . $genre['genre_name'] . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-price" onchange="filter()">
                                <option value="all">All Price</option>
                                <option value="50">Below 50</option>
                                <option value="100">Below 100</option>
                                <option value="200">Below 200</option>
                                <option value="300">Below 300</option>
                                <option value="over">Over 300</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="nk-input-slider-inline">
                                <input class="form-control" id="search_input" placeholder="Search Product" onkeyup="filter()">
                                </input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Products Filter -->
            <div class="nk-gap-3"></div>
            <!-- START: Products -->
            <div class="row vertical-gap" id="product">
            </div>
            <!-- END: Products -->

            <!-- START: Pagination -->
            <div class="nk-gap-3"></div>
            <div class="nk-pagination nk-pagination-center">
                <a href="#" class="nk-pagination-prev">
                    <span class="ion-ios-arrow-back"></span>
                </a>
                <nav>
                    <a class="nk-pagination-current" href="#">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <span>...</span>
                    <a href="#">14</a>
                </nav>
                <a href="#" class="nk-pagination-next">
                    <span class="ion-ios-arrow-forward"></span>
                </a>
            </div>
            <!-- END: Pagination -->


        </div>

        <div class="nk-gap-2"></div>



        <!-- START: Footer -->

        <!-- END: Footer -->


    </div>


    <!-- START: Scripts -->
    <?php include "./mod/add_script.php"; ?>
    <!-- END: Scripts -->


</body>
<script>
    const select_genre = document.getElementById('select_genre');
    const list_genre = document.getElementById('list_genre');
    select_genre.onclick = function(event) {
        event.stopPropagation();
        list_genre.style.display = 'flex';
    };

    document.addEventListener('click', function(event) {
        const isClickInside = list_genre.contains(event.target);
        if (list_genre.style.display === 'flex' && !isClickInside) {
            list_genre.style.display = 'none';
        }
    });
</script>
<!-- START: Get value list genre -->
<script>
    var genreItems = document.querySelectorAll('.genre_item');
    var list_genres = [];

    for (var i = 0; i < genreItems.length; i++) {
        var checkbox = genreItems[i].querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', function() {
            var labelFor = this.getAttribute('id');
            var correspondingLabel = document.querySelector('label[for="' + labelFor + '"]');

            if (this.checked) {
                var checkboxValue = this.value;
                correspondingLabel.style.backgroundColor = '#dd163b';
                list_genres.push(checkboxValue);
            } else {
                correspondingLabel.style.backgroundColor = '';
                var checkboxValue = this.value;
                var index = list_genres.indexOf(checkboxValue);
                if (index > -1) {
                    list_genres.splice(index, 1);
                }
            }
        });
    }
</script>
<!-- END: Get value list genre -->
<script>
    function filter() {
        //START JS: value classify
        var selectElement = document.querySelector('.form-control');
        var classify_value = selectElement.value;
        //END JS: value classify
        // ========================
        //START JS: value price
        var valuePrice = document.querySelector('.form-price');
        var price_value = valuePrice.value;
        //END JS: value price
        // =====================
        // START JS : input search
        var search_input = document.getElementById('search_input');
        var input_value = search_input.value;
        // END JS : input search
        // =======================
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("product").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "../mod/filter.php?classify=" + classify_value + "&genres=" + list_genres + "&price=" + price_value + "&search=" + input_value, true);
        xmlhttp.send();
    }
    window.onload = function() {
        filter();
    }
</script>

</html>