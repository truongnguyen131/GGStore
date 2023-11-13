<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $target_dir = "../../../uploads/";

    $query = "SELECT * FROM news WHERE id = ?";
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

    $title_news = $row["title"];
    $news_type_id = $row["news_type_id"];
    $publish_date = $row["publish_date"];
    $header = $row["header"];
    $footer = $row["footer"];

    $stmt->close();

    if (isset($_GET["task"]) && $_GET["task"] == "update") {
        $title_news = $_POST["title_news"];
        $news_type_id = $_POST["news_type_id"];
        $publish_date = $_POST["publish_date"];
        $header = $_POST["header"];
        $footer = $_POST["footer"];
        $title_paragraphs = $_POST["title_paragraphs"];
        $content = $_POST["content"];

        $query_update_news = "UPDATE `news` SET `title`= ?,`header`= ?,`footer`= ?,`publish_date`= ?,`news_type_id`= ? WHERE id = ?";
        $stmt_update_news = $conn->prepare($query_update_news);
        $stmt_update_news->bind_param("ssssii", $title_news, $header, $footer, $publish_date, $news_type_id, $id);
        $stmt_update_news->execute();
        $stmt_update_news->close();

        $old_image = array();
        $old_video = array();

        //select old image & video        
        $query_select_old_image_video = "SELECT image,video FROM `news_content` WHERE `news_id`= $id";
        $result_select_old_image_video = $conn->query($query_select_old_image_video);
        while ($row_old_image_video = $result_select_old_image_video->fetch_assoc()) {
            $old_image[] = $row_old_image_video['image'];
            $old_video[] = $row_old_image_video['video'];
        }

        //delete paragraphs old
        $query_delete = "DELETE FROM `news_content` WHERE `news_id`= $id";
        $stmt_delete = $conn->prepare($query_delete);
        $stmt_delete->execute();
        $stmt_delete->close();

        //upload new image & video 
        for ($i = 0; $i < count($_FILES["image"]['name']); $i++) {
            $image_name = $_FILES["image"]['name'][$i];
            $video_name = $_FILES["video"]['name'][$i];

            if ($image_name != "") {
                move_uploaded_file($_FILES["image"]["tmp_name"][$i], $target_dir . $image_name);
                if ($old_image[$i] != "") {
                    $targetPath = $target_dir . $old_image[$i];
                    if (file_exists($targetPath)) {
                        unlink($targetPath);
                    }
                }
            }

            if ($video_name != "") {
                move_uploaded_file($_FILES["video"]["tmp_name"][$i], $target_dir . $video_name);
                if ($old_video[$i] != "") {
                    $targetPath = $target_dir . $old_video[$i];
                    if (file_exists($targetPath)) {
                        unlink($targetPath);
                    }
                }
            }

        }

        // insert paragraphs
        for ($i = 0; $i < count($title_paragraphs); $i++) {
            $old_image_name = $old_image[$i];
            $old_video_name = $old_video[$i];
            $new_image_name = $_FILES["image"]['name'][$i];
            $new_video_name = $_FILES["video"]['name'][$i];

            $sql_insert_paragraph = "INSERT INTO `news_content`(`title`, `content`, `image`, `video`, `news_id`)
                    VALUES (?,?,?,?,?)";
            $stmt_insert_paragraph = $conn->prepare($sql_insert_paragraph);

            $image_name = $new_image_name;
            $video_name = $new_video_name;

            if ($new_image_name == "") {
                $image_name = $old_image_name;
            }

            if ($new_video_name == "") {
                $video_name = $old_video_name;
            }


            $stmt_insert_paragraph->bind_param("ssssi", $title_paragraphs[$i], $content[$i], $image_name, $video_name, $id);
            $stmt_insert_paragraph->execute();
            $stmt_insert_paragraph->close();
        }

        $conn->close();
        createNotification("Update News Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='update_news.php?id=$id';</script>";
    }

    if (isset($_GET["image"])) {
        $image_url = $_GET['image'];
        $id_content = $_GET['id_content'];
        $targetPath = $target_dir . $image_url;
        $sql = "UPDATE `news_content` SET `image`= ? WHERE id = ?";
        $image_delete = "";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $image_delete, $id_content);

        if ($stmt->execute()) {
            $targetPath = $target_dir . $image_url;
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            createNotification("Delete $image_url Successful!!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_news.php?id=$id';</script>";
        } else {
            createNotification("There was a problem update news! Update News Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_news.php?id=$id';</script>";
        }
        $stmt->close();
        $conn->close();
    }

    if (isset($_GET["video"])) {
        $video_url = $_GET['video'];
        $id_content = $_GET['id_content'];
        $targetPath = $target_dir . $video_url;
        $sql = "UPDATE `news_content` SET `video`= ? WHERE id = ?";
        $video_delete = "";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $video_delete, $id_content);

        if ($stmt->execute()) {
            $targetPath = $target_dir . $video_url;
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            createNotification("Delete $video_url Successful!!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_news.php?id=$id';</script>";
        } else {
            createNotification("There was a problem update news! Update News Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_news.php?id=$id';</script>";
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
    </style>

    <div class="container-fluid">
        <form name="frmUpdateUser" method="post" enctype="multipart/form-data">
            <h4 class="ico_mug">
                UPDATE NEWS
            </h4>
            <br>
            <table>
                <tr>
                    <td>
                        Type of news
                    </td>
                    <td>
                        <select name="news_type_id" id="news_type_id"
                            class="custom-select custom-select-sm form-control form-control-sm input-left">
                            <?php
                            $sql1 = "SELECT * FROM news_type";
                            $result_sql = $conn->query($sql1);

                            if ($result_sql->num_rows > 0) {
                                while ($row1 = $result_sql->fetch_assoc()) { ?>

                                    <option value="<?php echo $row1["id"]; ?>" <?= ($row1["id"] == $news_type_id) ? "selected" : "" ?>>
                                        <?php echo $row1["news_type_name"]; ?>
                                    </option>
                                    <?php
                                }
                            } else {
                                echo "";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Title
                    </td>
                    <td>
                        <input type="text" name="title_news" id="title_news" value="<?= $title_news ?>"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Publish date
                    </td>
                    <td>
                        <input type="date" name="publish_date" id="publish_date" value="<?= $publish_date ?>"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Header
                    </td>
                    <td>
                        <textarea name="header" id="header" cols="50" rows="5"
                            class="form-control form-control-sm input-left"><?= $header ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>
                        Paragraphs:
                    </td>
                    <td>
                        <div id="paragraphs">
                            <?php
                            $sql_sl_type = "SELECT * FROM news_content WHERE news_id = $id";
                            $result_sl_type = $conn->query($sql_sl_type);

                            if ($result_sl_type->num_rows > 0) {
                                while ($row = $result_sl_type->fetch_assoc()) {
                                    $title_paragraphs = $row["title"];
                                    $content = $row["content"];
                                    ?>

                                    <div> <br>
                                        <div class="row input-left">
                                            <div class="col-md-1">Title</div>
                                            <div class="col-md-11">
                                                <input type="text" name="title_paragraphs[]" value="<?= $title_paragraphs ?>"
                                                    class="form-control form-control-sm input-left">
                                            </div>
                                        </div>
                                        <div class="row input-left">
                                            <div class="col-md-1">Text</div>
                                            <div class="col-md-11">
                                                <textarea name="content[]" cols="50" rows="5"
                                                    class="form-control form-control-sm input-left"><?= $content ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row input-left">
                                            <div class="col-md-2">Image</div>
                                            <div class="col-md-6">

                                                <?php
                                                if ($row['image'] != "") { ?>
                                                    <div class="row">
                                                        <div class="col-md-7 input-left">
                                                            <a
                                                                href="update_news.php?id_content=<?= $row['id'] ?>&id=<?= $id ?>&image=<?= $row['image'] ?>">
                                                                <i class="fas fa-trash-alt"></i>_Delete_</a>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <img class="input-left" src="../../../uploads/<?php echo $row['image']; ?>"
                                                                width="200px" alt="image_avt">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-9">Replace image:</div>
                                                        <div class="col-md-3">
                                                            <input type="file" name="image[]" accept=".jpg, .jpeg, .png"
                                                                class="input-left">
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="file" name="image[]" accept=".jpg, .jpeg, .png" class="input-left">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row input-left">
                                            <div class="col-md-2">Video</div>
                                            <div class="col-md-6">
                                                <?php
                                                if ($row['video'] != "") { ?>
                                                    <div class="row">
                                                        <div class="col-md-7 input-left">
                                                            <a
                                                                href="update_news.php?id_content=<?= $row['id'] ?>&id=<?= $id ?>&video=<?= $row['video'] ?>">
                                                                <i class="fas fa-trash-alt"></i>_Delete_</a>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <video width="200px" class="input-left" controls>
                                                                <source src="../../../uploads/<?php echo $row['video']; ?>"
                                                                    type="video/mp4">
                                                            </video>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-9">Replace video:</div>
                                                        <div class="col-md-3">
                                                            <input type="file" name="video[]" accept=".mp4" class="input-left">
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="file" name="video[]" accept=".mp4" class="input-left">
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                            } else {
                                echo "";
                            }
                            ?>



                        </div>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <button type="button" onclick="add_sl_genre()" class="btn-circle btn-sm input-left">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" onclick="remove_sl_genre()" class="btn-circle btn-sm">
                            <i class="fas fa-minus"></i>
                        </button>
                    </td>
                </tr>
                <script>
                    function add_sl_genre() {
                        var paragraphsDiv = document.getElementById("paragraphs");

                        var divP = document.createElement("div");
                        paragraphsDiv.appendChild(divP);

                        divP.appendChild(document.createElement("br"));

                        var divTitle = document.createElement("div");
                        divTitle.className = "row input-left";

                        // Tạo phần tử "Title"
                        var titleDiv = document.createElement("div");
                        titleDiv.className = "col-md-1";
                        titleDiv.textContent = "Title";

                        // Tạo phần tử input cho "Title"
                        var titleInputDiv = document.createElement("div");
                        titleInputDiv.className = "col-md-11";

                        var titleInput = document.createElement("input");
                        titleInput.type = "text";
                        titleInput.name = "title_paragraphs[]";
                        titleInput.className = "form-control form-control-sm input-left";

                        // Thêm phần tử input vào phần tử "Title"
                        titleInputDiv.appendChild(titleInput);

                        // Thêm phần tử "Title" và phần tử input vào phần tử mới
                        divTitle.appendChild(titleDiv);
                        divTitle.appendChild(titleInputDiv);
                        divP.appendChild(divTitle);


                        var divText = document.createElement("div");
                        divText.className = "row input-left";

                        // Tạo phần tử "Text"
                        var textDiv = document.createElement("div");
                        textDiv.className = "col-md-1";
                        textDiv.textContent = "Text";

                        // Tạo phần tử input cho "Text"
                        var textInputDiv = document.createElement("div");
                        textInputDiv.className = "col-md-11";

                        var textArea = document.createElement("textarea");
                        textArea.name = "content[]";
                        textArea.cols = "50";
                        textArea.rows = "5";
                        textArea.className = "form-control form-control-sm input-left";

                        // Thêm phần tử textarea vào phần tử "Text"
                        textInputDiv.appendChild(textArea);

                        // Thêm phần tử "Text" và phần tử textarea vào phần tử mới
                        divText.appendChild(textDiv);
                        divText.appendChild(textInputDiv);
                        divP.appendChild(divText);

                        var divImage = document.createElement("div");
                        divImage.className = "row input-left";

                        // Tạo phần tử "Image"
                        var imageDiv = document.createElement("div");
                        imageDiv.className = "col-md-2";
                        imageDiv.textContent = "Image";

                        // Tạo phần tử input cho "Image"
                        var imageInputDiv = document.createElement("div");
                        imageInputDiv.className = "col-md-6";

                        var imageInput = document.createElement("input");
                        imageInput.type = "file";
                        imageInput.name = "image[]";
                        imageInput.accept = ".jpg, .jpeg, .png";
                        imageInput.className = "input-left";

                        // Thêm phần tử input vào phần tử "Image"
                        imageInputDiv.appendChild(imageInput);

                        // Thêm phần tử "Image" và phần tử input vào phần tử mới
                        divImage.appendChild(imageDiv);
                        divImage.appendChild(imageInputDiv);
                        divP.appendChild(divImage);


                        var divVideo = document.createElement("div");
                        divVideo.className = "row input-left";

                        // Tạo phần tử "Video"
                        var videoDiv = document.createElement("div");
                        videoDiv.className = "col-md-2";
                        videoDiv.textContent = "Video";

                        // Tạo phần tử input cho "Video"
                        var videoInputDiv = document.createElement("div");
                        videoInputDiv.className = "col-md-6";

                        var videoInput = document.createElement("input");
                        videoInput.type = "file";
                        videoInput.name = "video[]";
                        videoInput.accept = ".mp4";
                        videoInput.className = "input-left";

                        // Thêm phần tử input vào phần tử "Video"
                        videoInputDiv.appendChild(videoInput);

                        // Thêm phần tử "Video" và phần tử input vào phần tử mới
                        divVideo.appendChild(videoDiv);
                        divVideo.appendChild(videoInputDiv);
                        divP.appendChild(divVideo);

                    }

                    function remove_sl_genre() {
                        var paragraphsDiv = document.getElementById("paragraphs");
                        var lastDiv = paragraphsDiv.lastElementChild;
                        if (lastDiv) {
                            paragraphsDiv.removeChild(lastDiv);
                        }
                    }
                </script>

                <tr>
                    <td>
                        Footer
                    </td>
                    <td>
                        <textarea name="footer" id="footer" cols="50" rows="5"
                            class="form-control form-control-sm input-left"><?= $footer ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td id="error" class="error">

                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type="button" class="btn btn-info input-left" name="btnSave" value="Update"
                            onClick="save();">
                        <input type="button" class="btn btn-secondary" style=" margin-bottom: 10px;" name="btnBack"
                            value="Back" onClick="goback()">
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
        function save() {
            $('#error').html("");

            var title_news = document.frmUpdateUser.title_news.value.trim();
            var publish_date = document.frmUpdateUser.publish_date.value.trim();
            var header = document.frmUpdateUser.header.value.trim();
            var footer = document.frmUpdateUser.footer.value.trim();

            var title_paragraphs = document.getElementsByName("title_paragraphs[]");
            var contents = document.getElementsByName("content[]");

            if (title_news.length < 10 || !isNaN(title_news)) {
                $('#error').html("News title must be more than 10 characters!!!")
                document.frmUpdateUser.title_news.value = title_news;
                document.frmUpdateUser.title_news.focus();
                return false;
            }

            if (publish_date == "") {
                $('#error').html("Enter publish date!!!")
                return false;
            }

            if (header.length < 20 || !/^[\p{L}\p{N}\s.,?!'"-:]+$/u.test(header) || !isNaN(header)) {
                $('#error').html("News header must be more than 20 characters!!!")
                document.frmUpdateUser.header.value = header;
                document.frmUpdateUser.header.focus();
                return false;
            }

            var check = true;
            for (let index = 0; index < title_paragraphs.length; index++) {
                const element = title_paragraphs[index].value.trim();

                if (element.length < 10 || !/^[\p{L}\p{N}\s.,?!'"-:]+$/u.test(element) || !isNaN(element)) {
                    check = false;
                    var index_error = index + 1;
                    break;
                }

            }

            if (check == false) {
                $('#error').html("Paragraph title " + index_error + " must be more than 10 characters!!!")
                return false;
            }

            for (let index = 0; index < contents.length; index++) {
                const element = contents[index].value.trim();
                if (element.length < 20 || !/^[\p{L}\p{N}\s.,?!'"-:]+$/u.test(element) || !isNaN(element)) {
                    check = false;
                    var index_error = index + 1;
                    break;
                }
            }

            if (check == false) {
                $('#error').html("Paragraph content " + index_error + " must be more than 20 characters!!!")
                return false;
            }

            if (footer.length < 20 || !/^[\p{L}\p{N}\s.,?!'"-:]+$/u.test(footer) || !isNaN(footer)) {
                $('#error').html("News footer must be more than 20 characters!!!")
                document.frmUpdateUser.footer.value = footer;
                document.frmUpdateUser.footer.focus();
                return false;
            }

            document.frmUpdateUser.action = "update_news.php?id=<?= $id ?>&task=update";
            document.frmUpdateUser.submit();

        }

        function goback() {
            location.href = "news_management.php";
        }
    </script>

    <?php
}
?>