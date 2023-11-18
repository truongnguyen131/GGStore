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
    $reply_id = $row_dID["reply_id"];

    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'update') {
        $user_id = $_POST["user_id"];
        $news_id = $_POST["news_id"];
        $comment = $_POST["comment"];
        $comment_date = $_POST["comment_date"];
        $reply_id = ($_POST["reply_id"] == "") ? null : $_POST["reply_id"];

        $update_query = "UPDATE `news_comments` SET `comment`=?,`comment_date`=?,`news_id`=?,`user_id`=?, `reply_id`=? WHERE `id`=?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssiiii", $comment, $comment_date, $news_id, $user_id, $reply_id, $id);

        if ($update_stmt->execute()) {
            createNotification("Update Comment Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_news_comment.php?id=$id';</script>";
        } else {
            createNotification("An error occurred during the update process!! Update Comment Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='update_news_comment.php?id=$id';</script>";
        }
        $update_stmt->close();
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
                        Reply ID
                    </td>
                    <td>
                        <select name="reply_id" id="reply_id"
                            class="custom-select custom-select-sm form-control form-control-sm input-left">
                            <option value="">None</option>
                            <?php
                            $sql1 = "SELECT * FROM news_comments";
                            $result_sql = $conn->query($sql1);

                            if ($result_sql->num_rows > 0) {
                                while ($row1 = $result_sql->fetch_assoc()) { ?>
                                    <option value="<?php echo $row1["id"]; ?>" <?= ($reply_id == $row1["id"]) ? "selected" : "" ?>>
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