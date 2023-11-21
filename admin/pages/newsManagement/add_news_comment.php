<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'save') {
        $user_id = $_POST["user_id"];
        $news_id = $_POST["news_id"];
        $comment = $_POST["comment"];
        $comment_date = $_POST["comment_date"];
        $reply_id = ($_POST["reply_id"]=="") ? null :$_POST["reply_id"];

        //check news exists
        $check_query = "SELECT * FROM news_comments WHERE comment = ? and comment_date	 = ? and news_id = ? and user_id = ? and reply_id = ?";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt === false) {
            die("Error preparing statement");
        }
        $check_stmt->bind_param("ssiii", $comment, $comment_date, $news_id, $user_id, $reply_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            createNotification("Comment already exists! Add Comment Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_news_comment.php';</script>";
        } else {
            $insert_query = "INSERT INTO news_comments (user_id, news_id, comment, comment_date, reply_id) VALUES (?, ?, ?, ?, ?)";

            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("iissi", $user_id, $news_id, $comment, $comment_date, $reply_id);
            $insert_stmt->execute();
            $insert_stmt->close();

            createNotification("Add Comment Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_news_comment.php';</script>";
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
        <form name="frmAddUser" method="post">
            <h4 class="ico_mug">
                ADD A NEW COMMENT
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

                                    <option value="<?php echo $row1["id"]; ?>">
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

                                    <option value="<?php echo $row1["id"]; ?>">
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
                        <input type="text" class="form-control input-left" name="comment">
                    </td>
                </tr>
                <tr>
                    <td>
                        Date comment
                    </td>
                    <td>
                        <input type="datetime-local" class="form-control input-left" name="comment_date">
                    </td>
                </tr>
                <tr>
                    <td>
                        Reply to comment
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
                                    <option value="<?php echo $row1["id"]; ?>">
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
            var comment = document.frmAddUser.comment.value.trim();
            var comment_date = document.frmAddUser.comment_date.value;

            if (comment == "") {
                $('#error').html("Enter contents of comment!!!")
                return false;
            }

            if (comment_date == "") {
                $('#error').html("Enter date comment!!!")
                return false;
            }

            document.frmAddUser.action = "add_news_comment.php?task=save";
            document.frmAddUser.submit();
        }

        function goback() {
            location.href = "news_comments.php";
        }
    </script>

    <?php
}

?>