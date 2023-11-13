<?php
include("../add_template.php");

function delete_product($id_product)
{
    include("../../../mod/database_connection.php");
    $sql_delete = "DELETE FROM products WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_product);

    if ($stmt_delete->execute()) {
        echo "<script>location.href='add_product.php';</script>";
    } else {
        echo "Cannot delete product: " . $stmt_delete->error;
    }
    $stmt_delete->close();
    $conn->close();
}

function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'save') {
        $product_name = $_POST['txtProductname'];
        $classify = $_POST['slClassify'];
        $decs = $_POST['txtDesc'];
        $price = $_POST['txtPrice'];
        $id_manufacturer = $_POST['slManufacturer'];
        $date_release = $_POST['dateRelease'];
        $genres = $_POST['genres'];
        $targetDirectory = "../../../uploads/";
        $uploadSuccess = true;

        $fileAVT = basename($_FILES["fileAVT"]["name"]);
        $videoTrailer = basename($_FILES["videoTrailer"]["name"]);
        $fileSource = basename($_FILES["fileSource"]["name"]);

        //check product exists
        $check_query = "SELECT * FROM products WHERE product_name = ? and description = ? and id_manufacturer = ? and price = ? and release_date = ?";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt === false) {
            die("Error preparing statement");
        }
        $check_stmt->bind_param("ssiis", $product_name, $decs, $id_manufacturer, $price, $date_release);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            createNotification("Product already exists! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_product.php';</script>";
        } else {
            //insert product
            $sql_insert_product = "INSERT INTO `products`(`id_manufacturer`, `product_name`, `image_avt_url`, `description`, `video_trailer_url`, `source`, `price`, `release_date`,  `classify`) 
                                    VALUES (?,?,?,?,?,?,?,?,?)";

            $stmt_insert_product = $conn->prepare($sql_insert_product);
            $stmt_insert_product->bind_param("isssssiss", $id_manufacturer, $product_name, $fileAVT, $decs, $videoTrailer, $fileSource, $price, $date_release, $classify);

            if ($stmt_insert_product->execute()) {
                // id product just inserted
                $id_product = mysqli_insert_id($conn);
                //insert images 
                foreach ($_FILES['fileImgDetail']['tmp_name'] as $key => $tmp_name) {
                    $fileImgDetail = basename($_FILES['fileImgDetail']['name'][$key]);
                    $sql_insert_imgs = "INSERT INTO `product_images`(`product_id`, `image_url`) VALUES (?,?)";
                    $stmt_insert_imgs = $conn->prepare($sql_insert_imgs);
                    $stmt_insert_imgs->bind_param("is", $id_product, $fileImgDetail);
                    if ($stmt_insert_imgs->execute()) {
                        $check_result = true;
                    } else {
                        $check_result = false;
                        createNotification("The add-in process to the DB crashed! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                        delete_product($id_product);
                    }
                    $stmt_insert_imgs->close();
                }
                if ($check_result) {
                    //insert genres
                    for ($i = 0; $i < count($genres); $i++) {
                        $sql_insert_genres = "INSERT INTO `genre_product`(`genre_id`, `product_id`) VALUES (?,?)";
                        $stmt_insert_genres = $conn->prepare($sql_insert_genres);
                        $stmt_insert_genres->bind_param("ii", $genres[$i], $id_product);
                        if ($stmt_insert_genres->execute()) {
                            $check_result = true;
                        } else {
                            $check_result = false;
                            createNotification("The add-in process to the DB crashed! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                            delete_product($id_product);
                        }
                        $stmt_insert_genres->close();
                    }
                    if ($check_result) {
                        //upload fileImgDetail
                        foreach ($_FILES['fileImgDetail']['tmp_name'] as $key => $tmp_name) {
                            $fileImgDetail = basename($_FILES['fileImgDetail']['name'][$key]);
                            $targetPath = $targetDirectory . $fileImgDetail;

                            if (move_uploaded_file($tmp_name, $targetPath)) {
                                $uploadSuccess = true;
                            } else {
                                $file_error = $fileImgDetail;
                                $uploadSuccess = false;
                                break;
                            }
                        }
                        //Delete all successfully uploaded files when any file fails to upload
                        if (!$uploadSuccess) {
                            foreach ($_FILES['fileImgDetail']['name'] as $key => $filename) {
                                $fileImgDetail = basename($filename);
                                $targetPath = $targetDirectory . $fileImgDetail;
                                if (file_exists($targetPath)) {
                                    unlink($targetPath);
                                }
                            }
                            createNotification("Error when upload file $file_error! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                            delete_product($id_product);
                        }
                        //upload img_avt, video, source
                        else {
                            //upload image avt
                            $targetPath = $targetDirectory . $fileAVT;
                            if (move_uploaded_file($_FILES["fileAVT"]["tmp_name"], $targetPath)) {
                                //upload video trailer 
                                $targetPath = $targetDirectory . $videoTrailer;
                                if (move_uploaded_file($_FILES["videoTrailer"]["tmp_name"], $targetPath)) {
                                    //upload source zip 
                                    $targetPath = $targetDirectory . $fileSource;
                                    if (move_uploaded_file($_FILES["fileSource"]["tmp_name"], $targetPath)) {
                                        createNotification("Add Product Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                                        echo "<script>location.href='add_product.php';</script>";
                                    } else {
                                        $targetPath_fileAVT = $targetDirectory . $fileAVT;
                                        unlink($targetPath_fileAVT);
                                        $targetPath_videoTrailer = $targetDirectory . $videoTrailer;
                                        unlink($targetPath_videoTrailer);
                                        createNotification("Error when upload file $fileSource! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                                        delete_product($id_product);
                                    }
                                } else {
                                    $targetPath_fileAVT = $targetDirectory . $fileAVT;
                                    unlink($targetPath_fileAVT);
                                    createNotification("Error when upload file $videoTrailer! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                                    delete_product($id_product);
                                }
                            } else {
                                createNotification("Error when upload file $fileAVT! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                                delete_product($id_product);
                            }
                        }
                    }
                }

            } else {
                createNotification("There was a problem adding products! Add Product Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='add_product.php';</script>";
            }
            $stmt_insert_product->close();
        }
        
        $check_stmt->close();
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
                ADD A NEW PRODUCT
            </h4>
            <div class="table-container">
                <div class="table-half">
                    <table>
                        <tr>
                            <td>
                                Product Name
                            </td>
                            <td>
                                <input type="text" class="form-control input-left" name="txtProductname" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Description
                            </td>
                            <td>
                                <textarea class="form-control input-left" name="txtDesc" value="">

                                                </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Price
                            </td>
                            <td>
                                <input type="number" class="form-control input-left" min="0" max="10000000000" value="0"
                                    step="5000" name="txtPrice">
                            </td>
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
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <option value="<?php echo $row["id"]; ?>">
                                                <?php echo $row["full_name"]; ?>
                                            </option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Genre
                            </td>
                            <td>
                                <select class="form-control input-left" name="genres[]" multiple>
                                    <?php
                                    $sql = "SELECT * FROM genres ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <option value="<?php echo $row["id"]; ?>">
                                                <?php echo $row["genre_name"]; ?>
                                            </option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="table-half">
                    <table>
                        <tr>
                            <td>
                                Image avt
                            </td>
                            <td>
                                <input type="file" class="input-left" name="fileAVT" accept=".jpg, .jpeg, .png">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Image detail
                            </td>
                            <td>
                                <input type="file" class="input-left" name="fileImgDetail[]" multiple
                                    accept=".jpg, .jpeg, .png">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Video trailer
                            </td>
                            <td>
                                <input type="file" class="input-left" name="videoTrailer" accept=".mp4">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Source
                            </td>
                            <td>
                                <input type="file" class="input-left" name="fileSource" accept=".zip">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Release date
                            </td>
                            <td>
                                <input type="date" class="input-left" name="dateRelease" max="<?php echo date('Y-m-d'); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Classify
                            </td>
                            <td>
                                <select class="form-control input-left" style="width: 65%;" name="slClassify ">
                                    <option value="game">Game</option>
                                    <option value="gear">Gear</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td id="error" class="error"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="button" class="btn btn-info" name="btnSave" value="Save" onClick="save();">
                                <input type="button" class="btn btn-secondary" name="btnBack" value="Back"
                                    onClick="goback()">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


        </form>
    </div>

    <script>
        function save() {
            $('#error').html("");

            var product_name = document.frmAddProduct.txtProductname.value.trim();
            var decs = document.frmAddProduct.txtDesc.value.trim();
            var price = document.frmAddProduct.txtPrice.value.trim();
            var id_manufacturer = document.frmAddProduct.slManufacturer.value;
            var dateRelease = document.frmAddProduct.dateRelease.value;
            var genres = document.getElementsByName("genres[]");
            var fileAVT = document.frmAddProduct.fileAVT;
            var fileImgDetail = document.getElementsByName("fileImgDetail[]");
            var videoTrailer = document.frmAddProduct.videoTrailer;
            var fileSource = document.frmAddProduct.fileSource;

            if (product_name.length < 3) {
                $('#error').html("Product name must be more than 3 characters")
                document.frmAddProduct.txtProductname.value = product_name;
                document.frmAddProduct.txtProductname.focus();
                return false;
            }
            if (decs.length < 20 || !/^[\p{L}\p{N}\s.,?!'"-]+$/u.test(decs)) {
                $('#error').html("Improperly formatted description (must be more than 20 characters)");
                document.frmAddProduct.txtDesc.value = decs; document.frmAddProduct.txtDesc.focus();
                return false;
            } if (price == "" || isNaN(price)) {
                $('#error').html("The price must be numerical");
                document.frmAddProduct.txtPrice.value = price; document.frmAddProduct.txtPrice.focus();
                return false;
            }
            var selectedCount = 0;
            for (let index = 0; index < genres[0].options.length; index++) {
                if (genres[0].options[index].selected) { selectedCount++; }
            }
            if (selectedCount === 0) {
                $('#error').html("Select a genre!!!");
                return false;
            }
            if (fileAVT.files.length === 0) {
                $('#error').html("Select a image avt!!!");
                return false;
            } if (fileImgDetail[0].files.length === 0) {
                $('#error').html("Select a images detail!!!");
                return false;
            } if (videoTrailer.files.length === 0) {
                $('#error').html("Select a video trailer!!!");
                return false;
            } if (fileSource.files.length === 0) {
                $('#error').html("Select a file source!!!");
                return false;
            } if (dateRelease == "") {
                $('#error').html("Select a release date!!!");
                return false;
            } else {
                document.frmAddProduct.action = "add_product.php?task=save";
                document.frmAddProduct.submit();
            }

        }
        function goback() { location.href = "products_management.php"; }
    </script>

    <?php
}

?>