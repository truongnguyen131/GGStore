<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $task = isset($_GET["task"]) ? $_GET["task"] : "";

    if ($task == 'save') {

        $comment = $_POST['comment'];
        $comment_date = $_POST['comment_date'];
        $rating = $_POST['rating'];
        $product_id = $_POST['product_id'];
        $user_id = $_POST['user_id'];

        $check_query = "SELECT id FROM product_comments WHERE product_id = ? AND user_id = ? AND comment = ? AND rating = ? AND comment_date = ?";

        $check_stmt = $conn->prepare($check_query);

        $check_stmt->bind_param("iisis", $product_id, $user_id, $comment, $rating, $comment_date);

        $check_stmt->execute();

        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $check_stmt->close();
            $conn->close();
            createNotification("Comment already exists! Add Comment Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_product_comment.php';</script>";
        } else {
            $query = "INSERT INTO `product_comments`(`comment`, `comment_date`, `rating`, `product_id`, `user_id`) VALUES (?,?,?,?,?)";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("ssiii", $comment, $comment_date, $rating, $product_id, $user_id);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Add Comment Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='add_product_comment.php';</script>";
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
                        Comment content
                    </td>
                    <td>
                        <input type="text" class="form-control input-left" name="comment">
                    </td>
                </tr>
                <tr>
                    <td>
                        Comment date
                    </td>
                    <td>
                        <input type="date" class="form-control input-left" name="comment_date">
                    </td>
                </tr>
                <tr>
                    <td>
                        Rating star
                    </td>
                    <td>
                        <select class="form-control input-left" name="rating">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Product name
                    </td>
                    <td>
                        <select name="product_id" id="product_id" aria-controls="dataTable" class="form-control input-left">
                            <?php
                            $sql_product = "SELECT * FROM products";
                            $result_product = $conn->query($sql_product);
                            while ($row_product = $result_product->fetch_assoc()) { ?>
                                <option value="<?php echo $row_product["id"]; ?>">
                                    <?php echo $row_product["product_name"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        User name
                    </td>
                    <td>
                        <select name="user_id" id="user_id" aria-controls="dataTable" class="form-control input-left">
                            <?php
                            $sql_user = "SELECT * FROM users WHERE role = 'user'";
                            $result_user = $conn->query($sql_user);
                            while ($row_user = $result_user->fetch_assoc()) { ?>
                                <option value="<?php echo $row_user["id"]; ?>">
                                    <?php echo $row_user["full_name"]; ?>
                                </option>
                            <?php } ?>
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
            $('#error').html("")

            if (document.frmAddUser.comment.value.trim() == "") {
                $('#error').html("Enter comment content!!!")
                document.frmAddUser.comment.focus();
                return false;
            }
            if (document.frmAddUser.comment_date.value == "") {
                $('#error').html("Enter comment date!!!")
                return false;
            }
            else {
                document.frmAddUser.action = "add_product_comment.php?task=save";
                document.frmAddUser.submit();
            }
        }

        function goback() {
            location.href = "product_comments.php";
        }
    </script>

    <?php
}

?>