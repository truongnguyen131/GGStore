<?php
//  START: Connect Database 
session_start();
include_once('../mod/database_connection.php');
//  END: Connect Database 
// ======================================================
$page = isset($_POST['page']) ? $_POST['page'] : "";
$user_id = isset($_POST['us_id']) ? $_POST['us_id'] : "";
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : "";
// =================================================================
// START: Handling product comments and reviews
$input_cmt = isset($_POST['input_cmt']) ? $_POST['input_cmt'] : "";
$number_star = isset($_POST['star']) ? $_POST['star'] : "0";
$date = date("Y-m-d H:i:s");
if($page == 'comment'){
    if(!empty($user_id) && !empty($input_cmt) && !empty($product_id)){
        mysqli_query($conn, "INSERT INTO `products_comments`(`comment`, `comment_date`, `user_rating`, `product_id`, `user_id`) VALUES ('$input_cmt','$date','$number_star','$product_id','$user_id')");
        }
        echo "<script>
        alert('Bình luận thành công!!!')
        window.location.href = './store-product.php?product_id=$product_id';
        </script>";
    }
// END: Handling product comments and reviews
// =================================================================
?>

