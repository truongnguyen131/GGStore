<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    if (isset($_GET["task"]) && $_GET["task"] == "save") {
        $title_news = $_POST["title_news"];
        $news_type_id = $_POST["news_type_id"];
        $publish_date = $_POST["publish_date"];
        $header = $_POST["header"];
        $footer = $_POST["footer"];
        $title_paragraphs = $_POST["title_paragraphs"];
        $content = $_POST["content"];
        $target_dir = "../../../uploads/";

        //check news exists
        $check_query = "SELECT * FROM news WHERE title = ? and publish_date = ? and news_type_id = ?";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt === false) {
            die("Error preparing statement");
        }
        $check_stmt->bind_param("ssi", $title_news, $publish_date, $news_type_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            createNotification("News already exists! Add News Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_news.php';</script>";
        } else {
            //insert news
            $sql_insert_news = "INSERT INTO `news`(`title`, `header`, `footer`, `publish_date`, `news_type_id`) 
                                    VALUES (?,?,?,?,?)";

            $stmt_insert_news = $conn->prepare($sql_insert_news);
            $stmt_insert_news->bind_param("ssssi", $title_news, $header, $footer, $publish_date, $news_type_id);

            if ($stmt_insert_news->execute()) {
                // id_news just inserted
                $id_news = mysqli_insert_id($conn);

                //upload image & video
                for ($i = 0; $i < count($_FILES["image"]['name']); $i++) {
                    $image_name = $_FILES["image"]['name'][$i];
                    $video_name = $_FILES["video"]['name'][$i];
                    move_uploaded_file($_FILES["image"]["tmp_name"][$i], $target_dir . $image_name);
                    move_uploaded_file($_FILES["video"]["tmp_name"][$i], $target_dir . $video_name);
                }

                // insert paragraphs
                for ($i = 0; $i < count($title_paragraphs); $i++) {
                    $image_name = $_FILES["image"]['name'][$i];
                    $video_name = $_FILES["video"]['name'][$i];

                    $sql_insert_paragraph = "INSERT INTO `news_content`(`title`, `content`, `image`, `video`, `news_id`)
                    VALUES (?,?,?,?,?)";
                    $stmt_insert_paragraph = $conn->prepare($sql_insert_paragraph);
                    $stmt_insert_paragraph->bind_param("ssssi", $title_paragraphs[$i], $content[$i], $image_name, $video_name, $id_news);
                    $stmt_insert_paragraph->execute();
                    $stmt_insert_paragraph->close();
                }

                createNotification("Add News Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='add_news.php';</script>";
            } else {
                createNotification("There was a problem adding news! Add News Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='add_news.php';</script>";
            }


        }

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
                ADD A NEWS
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

                                    <option value="<?php echo $row1["id"]; ?>">
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
                        <input type="text" name="title_news" id="title_news"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Publish date
                    </td>
                    <td>
                        <input type="date" name="publish_date" id="publish_date"
                            class="form-control form-control-sm input-left">
                    </td>
                </tr>
                <tr>
                    <td>
                        Header
                    </td>
                    <td>
                        <textarea name="header" id="header" cols="50" rows="5"
                            class="form-control form-control-sm input-left"></textarea>
                    </td>
                </tr>


                <tr>
                    <td>
                        Paragraphs:
                    </td>
                    <td>
                        <div id="paragraphs">
                            <div> <br>
                                <div class="row input-left">
                                    <div class="col-md-1">Title</div>
                                    <div class="col-md-11">
                                        <input type="text" name="title_paragraphs[]"
                                            class="form-control form-control-sm input-left">
                                    </div>
                                </div>
                                <div class="row input-left">
                                    <div class="col-md-1">Text</div>
                                    <div class="col-md-11">
                                        <textarea name="content[]" cols="50" rows="5"
                                            class="form-control form-control-sm input-left"></textarea>
                                    </div>
                                </div>
                                <div class="row input-left">
                                    <div class="col-md-2">Image</div>
                                    <div class="col-md-6">
                                        <input type="file" name="image[]" accept=".jpg, .jpeg, .png" class="input-left">
                                    </div>
                                </div>
                                <div class="row input-left">
                                    <div class="col-md-2">Video</div>
                                    <div class="col-md-6">
                                        <input type="file" name="video[]" accept=".mp4" class="input-left">
                                    </div>
                                </div>
                            </div>

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
                            class="form-control form-control-sm input-left"></textarea>
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
                        <input type="button" class="btn btn-info input-left" name="btnSave" value="Save" onClick="save();">
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

            document.frmUpdateUser.action = "add_news.php?task=save";
            document.frmUpdateUser.submit();

        }

        function goback() {
            location.href = "news_management.php";
        }
    </script>

    <?php
}
?>