<?php
session_start();
include_once '../mod/database_connection.php';
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
                <li><a href="../Galaxy_Game_Store/store">Store</a></li>
                <li><span class="fa fa-angle-right"></span></li>
                <li><span>Store</span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <!-- END: Breadcrumbs -->

        <div class="container">


            <!-- START: Products Filter -->
            <div class="nk-gap-2"></div>
            <div class="row vertical-gap">
                <div class="col-lg-12">
                    <div class="row vertical-gap">
                        <div class="col-md-3">
                            <select class="form-control" id="classify_product" onchange="filter()">
                                <option value="all">Classify</option>
                                <option value="game">Game</option>
                                <option value="gear">Gear</option>
                                <option value="exchange">Exchange</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="select_genre" id="select_genre">
                                <span>Genres</span>
                                <ion-icon name="chevron-down-outline"></ion-icon>
                            </div>
                            <div class="list_genre" id="list_genre">
                                <?php
                                $sql_sl_genres = "SELECT * FROM `genres`";
                                $result_genres = $conn->query($sql_sl_genres);
                                ?>
                                <div class="list_genreof_type">
                                    <?php while ($row_genre = $result_genres->fetch_assoc()) { ?>
                                        <label for="<?= $row_genre['id'] ?>" class="genre_item" style="display: block;"
                                            onchange="filter()">
                                            <input type="checkbox" id="<?= $row_genre['id'] ?>"
                                                value="<?= $row_genre['id'] ?>">
                                            <span>
                                                <?= $row_genre['genre_name'] ?>
                                            </span>
                                        </label>
                                    <?php } ?>
                                </div>



                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-price" onchange="filter()">
                                <option value="all">Price</option>
                                <option value="50">Below 50</option>
                                <option value="100">Below 100</option>
                                <option value="200">Below 200</option>
                                <option value="300">Below 300</option>
                                <option value="over">Over 300</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="nk-input-slider-inline">
                                <input class="form-control" id="search_input" placeholder="Search" onkeyup="filter()">
                                </input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Products Filter -->


            <div class="nk-gap-3"></div>
            <!-- START: Products -->
            <div id="show_product">

            </div>

            <!-- END: Products -->

            <input type="number" value="1" hidden id="currentPage">

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

<script>
    const select_genre = document.getElementById('select_genre');
    const list_genre = document.getElementById('list_genre');
    select_genre.onclick = function (event) {
        event.stopPropagation();
        list_genre.style.display = 'block';
    };

    document.addEventListener('click', function (event) {
        const isClickInside = list_genre.contains(event.target);
        if (list_genre.style.display === 'block' && !isClickInside) {
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
        checkbox.addEventListener('change', function () {
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

<!-- START: Pagination JS-->
<script>

    function prevBtn() {
        const currentPageID = document.querySelector('.nk-pagination-current').id;
        const pageParts = currentPageID.split('_');
        currentPageNumber = Number(pageParts[1]);

        if (currentPageNumber > 1) {
            currentPageNumber -= 1;
            page_id = "page_" + currentPageNumber;
            change_page(page_id);
        }
    }


    function nextBtn() {
        const currentPageID = document.querySelector('.nk-pagination-current').id;
        const pageParts = currentPageID.split('_');
        currentPageNumber = Number(pageParts[1]);
        const paginationNav = document.getElementById('Pagination');
        const links = paginationNav.querySelectorAll('a');
        const totalPage = links.length;

        if (currentPageNumber < totalPage) {
            currentPageNumber += 1;
            page_id = "page_" + currentPageNumber;
            change_page(page_id);
        }
    }

    function change_page(id) {
        currentPage = document.querySelector('.nk-pagination-current');
        currentPage.classList.remove('nk-pagination-current');
        document.getElementById(id).classList.add('nk-pagination-current');
        document.getElementById("currentPage").value = document.getElementById(id).innerText;
        filter();
    }
</script>

<!-- END: filter() -->
<script>
    function filter() {

        var selectElement = document.querySelector('.form-control');
        var classify_value = selectElement.value;

        var valuePrice = document.querySelector('.form-price');
        var price_value = valuePrice.value;

        var search_input = document.getElementById('search_input');
        var search = search_input.value;


        var currentPage = document.getElementById("currentPage").value;

        var postData = {
            search: search,
            classify_product: classify_value,
            list_genres: list_genres,
            price: price_value,
            currentPage: currentPage
        };

        $.post('../Galaxy_Game_Store/pages/filter.php', { postData }, function (data) {
            $('#show_product').html(data);
        });

    }

    window.onload = function () {

        <?php
        if (isset($_GET['id_category'])) { ?>
            document.getElementById(<?= $_GET['id_category'] ?>).click();
        <?php }

        if (isset($_GET['classify'])) { ?>
            var select = document.getElementById("classify_product");
            var options = select.options;
            for (var i = 0; i < options.length; i++) {
                if (options[i].value == "<?= $_GET['classify'] ?>") {
                    options[i].selected = true;
                }
            }
        <?php } ?>

        filter();
    }
</script>



</html>