<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $id = $_GET['id'];
    $targetDirectory = "../../../uploads/";

    //delete img avt & video
    $query_sl_product = "SELECT image_avt_url,video_trailer_url FROM products WHERE id = ?";
    $stmt_sl_product = $conn->prepare($query_sl_product);

    if (!$stmt_sl_product) {
        die("Prepare failed");
    }
    $stmt_sl_product->bind_param("i", $id);
    if (!$stmt_sl_product->execute()) {
        die("Execute failed: " . $stmt_sl_product->error);
    }
    $result_sl_product = $stmt_sl_product->get_result();
    $row_sl_product = $result_sl_product->fetch_assoc();
    $img_avt = $row_sl_product['image_avt_url'];
    $video_trailer = $row_sl_product['video_trailer_url'];

    $targetPath = $targetDirectory . $img_avt;
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }

    $targetPath = $targetDirectory . $video_trailer;
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }

    $stmt_sl_product->close();


    //delete imgs detail
    $query_sl_imgs = "SELECT image_url  FROM product_images WHERE product_id = ?";
    $stmt_sl_imgs = $conn->prepare($query_sl_imgs);

    if (!$stmt_sl_imgs) {
        die("Prepare failed");
    }
    $stmt_sl_imgs->bind_param("i", $id);
    if (!$stmt_sl_imgs->execute()) {
        die("Execute failed: " . $stmt_sl_imgs->error);
    }
    $result_sl_imgs = $stmt_sl_imgs->get_result();
    while ($row_sl_imgs = $result_sl_imgs->fetch_assoc()) {
        $targetPath = $targetDirectory . $row_sl_imgs['image_url'];
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }

    $stmt_sl_imgs->close();

    $query = "DELETE FROM `products` WHERE id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed");
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
    createNotification("Delete Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
    echo "<script>location.href='products_management.php';</script>";
}
?>