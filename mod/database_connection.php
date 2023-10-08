<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'galaxy_game_store') or die("Cannot connetion with database: " . mysqli_connect_error());
if (!$conn) {
    die("Connection to database failed: " . mysqli_connect_error());
} else {
    mysqli_set_charset($conn, "utf8");
}
?>