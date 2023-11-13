<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");

    $id = $_GET['id'];
    $sql_dID = "SELECT * FROM news_comments WHERE id = $id";
    $result_dID = $conn->query($sql_dID);
    $row_dID = $result_dID->fetch_assoc();

    $user_id = $row_dID["user_id"];
    $news_id = $row_dID["news_id"];
    $comment = $row_dID["comment"];
    $comment_date = $row_dID["comment_date"];

    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {
        $user_id = $_POST["user_id"];
        $news_id = $_POST["news_id"];
        $comment = $_POST["comment"];
        $comment_date = $_POST["comment_date"];

        $sql_check_duplicate = "SELECT * FROM news_comments WHERE comment = '$comment' AND comment_date = '$comment_date' AND news_id = $news_id AND user_id = $user_id";
        $result_check_duplicate = $conn->query($sql_check_duplicate);

        if ($result_check_duplicate->num_rows > 0) {
            createNotification("Duplicated updated data!! Update Comment Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_news_comment.php?id=$id';</script>";
        } else {
            $sql_update = "UPDATE `news_comments` SET `comment`='$comment',`comment_date`='$comment_date',`news_id`='$news_id',`user_id`='$user_id' WHERE `id`= $id";
            if ($conn->query($sql_update) === TRUE) {
                createNotification("Update Comment Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='update_news_comment.php?id=$id';</script>";
            } else {
                createNotification("An error occurred during the update process!! Update Comment Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
                echo "<script>location.href='update_news_comment.php?id=$id';</script>";
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
        <form name="frmUpdateUser" method="post">
            <h4 class="ico_mug">
                UPDATE COMMENT
            </h4>
            <table>
                <tr>
                    <td>
                        User ID
                    </td>
                    <td>
                        <select name="user_id" id="user_id"
                            class="custom-select custom-select-sm form-control form-control-sm input-left">
                            <?php
                            $sql1 = "SELECT * FROM users WHERE role = 'user'";
                            $result_sql = $conn->query($sql1);

                            if ($result_sql->num_rows > 0) {
                                while ($row1 = $result_sql->fetch_assoc()) { ?>

                                    <option value="<?php echo $row1["id"]; ?>" <?= ($user_id == $row1["id"]) ? "selected" : "" ?>>
                                        <?php echo $row1["full_name"]; ?>
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
                        News ID
                    </td>
                    <td>
                        <select name="news_id" id="news_id"
                            class="custom-select custom-select-sm form-control form-control-sm input-left">
                            <?php
                            $sql1 = "SELECT * FROM news";
                            $result_sql = $conn->query($sql1);

                            if ($result_sql->num_rows > 0) {
                                while ($row1 = $result_sql->fetch_assoc()) { ?>

                                    <option value="<?php echo $row1["id"]; ?>" <?= ($news_id == $row1["id"]) ? "selected" : "" ?>>
                                        <?php echo $row1["id"]; ?>
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
                        Content
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="comment" value="<?= $comment ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        Date comment
                    </td>
                    <td>
                        <input type="datetime-local" class="form-control input-left" value="<?= $comment_date ?>"
                            name="comment_date">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="error" class="error"></td>
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
            var comment = document.frmUpdateUser.comment.value.trim();
            var comment_date = document.frmUpdateUser.comment_date.value;

            if (comment == "") {
                $('#error').html("Enter contents of comment!!!")
                return false;
            }

            if (comment_date == "") {
                $('#error').html("Enter date comment!!!")
                return false;
            }

            document.frmUpdateUser.action = "update_news_comment.php?task=update&id=<?= $id ?>";
            document.frmUpdateUser.submit();
        }

        function goback() {
            location.href = "news_comments.php";
        }
    </script>

    <?php
}
?>