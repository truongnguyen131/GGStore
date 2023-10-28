<?php
include("../add_template.php");

function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";
    $id = $_GET['id'];
    $targetDirectory = "../../../uploads/";
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed");
    }
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $product_name = $row['product_name'];
    $decs = $row['description'];
    $price = $row['price'];
    $img_avt = $row['image_avt_url'];
    $video_trailer = $row['video_trailer_url'];
    $file_source = $row['source'];
    $date_release = $row['release_date'];

    $stmt->close();

    if ($task == 'update') {
        $product_name = $_POST['txtProductname'];
        $decs = $_POST['txtDesc'];
        $price = $_POST['txtPrice'];
        $id_manufacturer = $_POST['slManufacturer'];
        $date_release = $_POST['dateRelease'];
        $genres = $_POST['genres'];
        $count = 1;

        //select image_url push in $imgs_product array
        $query_sl_img_product = "SELECT image_url FROM product_images WHERE product_id = ?";
        $stmt_sl_img_product = $conn->prepare($query_sl_img_product);
        $stmt_sl_img_product->bind_param("i", $id);
        $stmt_sl_img_product->execute();
        $result_sl_img_product = $stmt_sl_img_product->get_result();
        $imgs_product = array();
        while ($row = $result_sl_img_product->fetch_assoc()) {
            $imgs_product[] = $row['image_url'];
        }

        //UPDATE image_urls 
        while ($row = $result_sl_img_product->fetch_assoc()) {
            $image_url_name = $row['image_url'];
            $imgs_product[] = $image_url_name;
            if (
                $_FILES[$count]["name"] != "" && $_FILES[$count]["name"] != $image_url_name &&
                !in_array($_FILES[$count]["name"], $imgs_product)
            ) {
                $targetPath = $targetDirectory . $image_url_name;
                if (file_exists($targetPath)) {
                    unlink($targetPath);
                }
                $image_url_name_new = basename($_FILES[$count]["name"]);
                $targetPath = $targetDirectory . $image_url_name_new;
                move_uploaded_file($_FILES[$count]["tmp_name"], $targetPath);

                $query_update_img_product = "UPDATE product_images SET image_url = ? WHERE product_id = ? and image_url = ?";
                $stmt_update_img_product = $conn->prepare($query_update_img_product);
                $stmt_update_img_product->bind_param("sis", $image_url_name_new, $id, $image_url_name);
                $stmt_update_img_product->execute();
                $stmt_update_img_product->close();
            }
            $count++;
        }
        $stmt_sl_img_product->close();

        // ADD new images_url
        $hasDuplicateImgs = false;
        if (isset($_FILES['fileImgDetail']) && $_FILES['fileImgDetail']['name'][0] != "") {
            foreach ($_FILES['fileImgDetail']['tmp_name'] as $key => $tmp_name) {
                $fileImgDetail = basename($_FILES['fileImgDetail']['name'][$key]);
                if (in_array($fileImgDetail, $imgs_product)) {
                    $hasDuplicateImgs = true;
                    break;
                }
            }
            if ($hasDuplicateImgs) {
                createNotification("$fileImgDetail has been duplicated! Update Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='update_product.php?id=$id';</script>";
                exit();
            } else {
                foreach ($_FILES['fileImgDetail']['tmp_name'] as $key => $tmp_name) {
                    $fileImgDetail = basename($_FILES['fileImgDetail']['name'][$key]);
                    $sql_insert_imgs = "INSERT INTO `product_images`(`product_id`, `image_url`) VALUES (?,?)";
                    $stmt_insert_imgs = $conn->prepare($sql_insert_imgs);
                    $stmt_insert_imgs->bind_param("is", $id, $fileImgDetail);
                    if ($stmt_insert_imgs->execute()) {
                        $targetPath = $targetDirectory . $fileImgDetail;
                        move_uploaded_file($tmp_name, $targetPath);
                    } else {
                        createNotification("The add $fileImgDetail process to the DB crashed! Update Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                        echo "<script>location.href='update_product.php?id=$id';</script>";
                    }
                    $stmt_insert_imgs->close();
                }
            }
        }

        //UPDATE genres 
        $query_sl_genre_product = "SELECT genre_id FROM genre_product WHERE product_id = $id";
        $result_genre_product = $conn->query($query_sl_genre_product);
        $genreIds = array();
        while ($row = $result_genre_product->fetch_assoc()) {
            $genreIds[] = $row["genre_id"];
        }
        $differences = array_diff($genres, $genreIds);
        if (!empty($differences) || count($genres) != count($genreIds)) {
            $query_delete_genre_product = "DELETE FROM genre_product WHERE product_id = $id";
            $conn->query($query_delete_genre_product);
            for ($i = 0; $i < count($genres); $i++) {
                $sql_insert_genres = "INSERT INTO `genre_product`(`genre_id`, `product_id`) VALUES (?,?)";
                $stmt_insert_genres = $conn->prepare($sql_insert_genres);
                $stmt_insert_genres->bind_param("ii", $genres[$i], $id);
                $stmt_insert_genres->execute();
                $stmt_insert_genres->close();
            }
        }


        if ($_FILES["fileAVT"]["name"] != "" && $_FILES["fileAVT"]["name"] != $img_avt) {
            $targetPath = $targetDirectory . $img_avt;
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            $img_avt = basename($_FILES["fileAVT"]["name"]);
            $targetPath = $targetDirectory . $img_avt;
            move_uploaded_file($_FILES["fileAVT"]["tmp_name"], $targetPath);
        }
        if ($_FILES["videoTrailer"]["name"] != "" && $_FILES["videoTrailer"]["name"] != $video_trailer) {
            $targetPath = $targetDirectory . $video_trailer;
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            $video_trailer = basename($_FILES["videoTrailer"]["name"]);
            $targetPath = $targetDirectory . $video_trailer;
            move_uploaded_file($_FILES["videoTrailer"]["tmp_name"], $targetPath);
        }
        if ($_FILES["fileSource"]["name"] != "" && $_FILES["fileSource"]["name"] != $file_source) {
            $targetPath = $targetDirectory . $file_source;
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            $file_source = basename($_FILES["fileSource"]["name"]);
            $targetPath = $targetDirectory . $file_source;
            move_uploaded_file($_FILES["fileSource"]["tmp_name"], $targetPath);
        }

        $query_update_product = "UPDATE `products` SET 
            `id_manufacturer` = ?, `product_name` = ?,
            `image_avt_url` = ?, `description` = ?,
            `video_trailer_url` = ?, `source` = ?, `price` = ?,
            `release_date` = ? WHERE id = ?";

        $stmt_update_product = $conn->prepare($query_update_product);
        $stmt_update_product->bind_param("isssssisi", $id_manufacturer, $product_name, $img_avt, $decs, $video_trailer, $file_source, $price, $date_release, $id);
        $stmt_update_product->execute();
        $stmt_update_product->close();
        $conn->close();
        createNotification("Update Product Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='update_product.php?id=$id';</script>";
    }

    if ($task == 'delete') {
        $image_url = $_GET['image_url'];
        $targetPath = $targetDirectory . $image_url;
        $sql = "DELETE FROM product_images WHERE product_id = ? AND image_url = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $id, $image_url);

        if ($stmt->execute()) {
            $targetPath = $targetDirectory . $image_url;
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            createNotification("Delete $image_url Successful!!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_product.php?id=$id';</script>";
        } else {
            createNotification("There was a problem update products! Update Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_product.php?id=$id';</script>";
        }
        $stmt->close();
        $conn->close();
    }
    ?>

    <style>
        .input-left {
            margin-left: 10px;
            margin-bottom: 10px;
        }

        .error {
            padding-left: 20px;
            font-size: 15px;
            color: red;
        }

        .table-container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .table-half {
            flex-basis: 50%;
            padding: 10px;
            box-sizing: border-box;
        }

        @media screen and (max-width: 1023px) {
            .table-half {
                flex-basis: 100%;
            }
        }
    </style>

    <div class="container-fluid">
        <form name="frmAddProduct" method="post" enctype="multipart/form-data">
            <h4 class="ico_mug">
                UPDATE PRODUCT
            </h4>
            <div class="table-container">
                <table>
                    <tr>
                        <td>
                            Product Name
                        </td>
                        <td>
                            <input type="text" class="form-control input-left" name="txtProductname"
                                value="<?php echo $product_name; ?>">
                        </td>
                        <td id="errorProductname" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Description
                        </td>
                        <td>
                            <textarea class="form-control input-left" name="txtDesc"><?php echo $decs; ?></textarea>

                        <td id="errorDesc" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Price
                        </td>
                        <td>
                            <input type="number" class="form-control input-left" min="0" max="10000000000" step="5000"
                                name="txtPrice" value="<?php echo $price; ?>">
                        </td>
                        <td id="errorPrice" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Manufacturer
                        </td>
                        <td>
                            <select class="form-control input-left" name="slManufacturer">
                                <?php
                                $sql = "SELECT * FROM users WHERE role = 'manufacturer'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        if ($row["id"] == $id_manufacturer) { ?>
                                            <option value="<?php echo $row["id"]; ?>" selected>
                                                <?php echo $row["full_name"]; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option value="<?php echo $row["id"]; ?>">
                                                <?php echo $row["full_name"]; ?>
                                            </option>
                                        <?php }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td id="errorManufacturer" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Genre
                        </td>
                        <td>
                            <?php
                            $sql_genres_product = "SELECT genre_id  FROM genre_product WHERE product_id = ?";
                            $stmt_genres_product = $conn->prepare($sql_genres_product);

                            if (!$stmt_genres_product) {
                                die("Prepare failed");
                            }
                            $stmt_genres_product->bind_param("i", $id);

                            if (!$stmt_genres_product->execute()) {
                                die("Execute failed: " . $stmt_genres_product->error);
                            }

                            $result_genres_product = $stmt_genres_product->get_result();
                            while ($row_genres_product = $result_genres_product->fetch_assoc()) { ?>
                                <select class="input-left" name="genres[]">
                                    <?php
                                    $sql_genres = "SELECT * FROM genres ";
                                    $result_genres = $conn->query($sql_genres);
                                    while ($row_genres = $result_genres->fetch_assoc()) {
                                        if ($row_genres["id"] == $row_genres_product["genre_id"]) { ?>
                                            <option value="<?php echo $row_genres["id"]; ?>" selected>
                                                <?php echo $row_genres["genre_name"]; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option value="<?php echo $row_genres["id"]; ?>">
                                                <?php echo $row_genres["genre_name"]; ?>
                                            </option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            <?php }
                            $stmt_genres_product->close(); ?>
                            <span id="add_genre">

                            </span><br>
                            <button type="button" onclick="add_sl_genre()" class="btn-circle btn-sm input-left">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" onclick="remove_sl_genre()" class="btn-circle btn-sm">
                                <i class="fas fa-minus"></i>
                            </button>
                            <?php
                            $sql_genres = "SELECT * FROM genres";
                            $result_genres = $conn->query($sql_genres);
                            $genreData = array();
                            while ($row_genres = $result_genres->fetch_assoc()) {
                                $genreData[] = $row_genres;
                            }
                            $genreDataJSON = json_encode($genreData);
                            ?>
                            <script>
                                function add_sl_genre() {
                                    var genreData = <?php echo $genreDataJSON; ?> ;
                                    var genres = document.getElementsByName("genres[]");
                                    var selectedGenres = Array.from(genres).map(option => option.value);
                                    const select = document.createElement('select');
                                    select.className = 'input-left';
                                    select.name = "genres[]";
                                    for (var i = 0; i < genreData.length; i++) {
                                        var option = document.createElement("option");
                                        option.value = genreData[i].id;
                                        option.textContent = genreData[i].genre_name;
                                        select.appendChild(option);
                                    }
                                    if (selectedGenres.length % 3 == 0 && selectedGenres != 0) {
                                        document.getElementById('add_genre').appendChild(document.createElement('br'));
                                    }
                                    document.getElementById('add_genre').appendChild(select);
                                }

                                function remove_sl_genre() {
                                    var genres = document.getElementsByName("genres[]");
                                    var selectedGenres = Array.from(genres).map(option => option.value);
                                    if (selectedGenres.length > 0) {
                                        var lastSelect = genres[genres.length - 1];
                                        lastSelect.parentNode.removeChild(lastSelect);
                                        if ((selectedGenres.length - 1) % 3 == 0) {
                                            var addGenreDiv = document.getElementById("add_genre");
                                            addGenreDiv.removeChild(addGenreDiv.lastElementChild);
                                        }
                                    }
                                }
                            </script>
                        </td>

                        <td id="errorGenre" class="error"></td>
                    </tr>

                    <tr>
                        <td>
                            Image avt
                        </td>
                        <td>
                            <img class="input-left" src="../../../uploads/<?php echo $img_avt; ?>" width="200px"
                                alt="image_avt">
                            <input type="file" class="input-left" name="fileAVT" accept=".jpg, .jpeg, .png">
                        </td>
                        <td id="errorfileAVT" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Image detail
                        </td>
                        <td>
                            <?php
                            $sql_imgs_detail = "SELECT * FROM product_images WHERE product_id = ?";
                            $stmt_imgs_detail = $conn->prepare($sql_imgs_detail);
                            if (!$stmt_imgs_detail) {
                                die("Prepare failed");
                            }
                            $stmt_imgs_detail->bind_param("i", $id);

                            if (!$stmt_imgs_detail->execute()) {
                                die("Execute failed: " . $stmt_imgs_detail->error);
                            }
                            $result_imgs_detail = $stmt_imgs_detail->get_result();
                            $count = 1;
                            while ($row = $result_imgs_detail->fetch_assoc()) { ?>
                                <a
                                    href="update_product.php?id=<?php echo $id; ?>&image_url=<?php echo $row['image_url']; ?>&task=delete">
                                    <i class="fas fa-trash-alt"></i>__Delete__</a>
                                <img class="input-left" src="../../../uploads/<?php echo $row['image_url']; ?>" width="200px"
                                    alt="image_avt">
                                <input type="file" class="input-left" name="<?php echo $count; ?>" accept=".jpg, .jpeg, .png">
                                <br>
                                <?php $count++;
                            }
                            ?><span>Select more</span>&nbsp;
                            <input type="file" class="input-left" name="fileImgDetail[]" multiple
                                accept=".jpg, .jpeg, .png">
                            <?php ?>
                        </td>
                        <td id="errorfileImgDetail" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Video trailer
                        </td>
                        <td>
                            <video width="200px" class="input-left" controls>
                                <source src="../../../uploads/<?php echo $video_trailer; ?>" type="video/mp4">
                            </video>
                            <input type="file" name="videoTrailer" accept=".mp4">
                        </td>
                        <td id="errorvideoTrailer" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Source
                        </td>
                        <td>
                            <a href="../../../uploads/<?php echo $file_source; ?>">
                                <?php echo $file_source; ?>
                            </a>
                            <input type="file" class="input-left" name="fileSource" accept=".zip">
                        </td>
                        <td id="errorfileSource" class="error"></td>
                    </tr>
                    <tr>
                        <td>
                            Release date
                        </td>
                        <td>
                            <input type="date" class="input-left" name="dateRelease" value="<?php echo $date_release; ?>"
                                max="<?php echo date('Y-m-d'); ?>">
                        </td>
                        <td id="errorDateRelease" class="error"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="button" class="btn btn-info" name="btnSave" value="Update" onClick="update();">
                            <input type="button" class="btn btn-secondary" name="btnBack" value="Back" onClick="goback()">
                        </td>
                    </tr>
                </table>
            </div>


        </form>
    </div>

    <script>
        function update() {
            var genres = document.getElementsByName("genres[]");
            var selectedGenres = Array.from(genres).map(option => option.value);
            var hasDuplicates = selectedGenres.some((genre, index) => selectedGenres.indexOf(genre) !== index);
            var product_name = document.frmAddProduct.txtProductname.value.trim();
            var decs = document.frmAddProduct.txtDesc.value.trim();
            var price = document.frmAddProduct.txtPrice.value.trim();
            var dateRelease = document.frmAddProduct.dateRelease.value;
            $('#errorProductname').html(""); $('#errorDesc').html("");
            $('#errorPrice').html(""); $('#errorManufacturer').html("");
            $('#errorGenre').html(""); $('#errorDateRelease').html("");
            if (product_name.length < 3) {
                $('#errorProductname').html("Product name must be more than 3 characters");
                document.frmAddProduct.txtProductname.value = product_name;
                document.frmAddProduct.txtProductname.focus();
                return false;
            }
            if (decs.length < 20 || !/^[\p{L}\p{N}\s.,?!"-]+$/u.test(decs)) {
                $('#errorDesc').html("Improperly formatted description");
                document.frmAddProduct.txtDesc.value = decs;
                document.frmAddProduct.txtDesc.focus();
                return false;
            }
            if (price == "" || isNaN(price)) {
                $('#errorPrice').html("The price must be numerical");
                document.frmAddProduct.txtPrice.value = price;
                document.frmAddProduct.txtPrice.focus();
                return false;
            }
            if (selectedGenres.length == 0) {
                $('#errorGenre').html("Select a genre for product!!!");
                return false;
            }
            if (hasDuplicates) {
                $('#errorGenre').html("Duplicate genres!!!");
                return false;
            } if (dateRelease == "") {
                $('#errorDateRelease').html("Select a release date!!!");
                return false;
            }
            else {
                document.frmAddProduct.action = "update_product.php?id=<?php echo $id; ?>&task=update"; document.frmAddProduct.submit();
            }
        } function goback() {
            location.href = "products_management.php";
        }
    </script>

    <?php
}

?>