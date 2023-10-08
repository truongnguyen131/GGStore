<?php
session_start();

$time = isset($_POST['time']) ? $_POST['time'] : "";
if ($time == "all") {
    unset($_SESSION['notifications']);
} else {
    $notificationCount = count($_SESSION['notifications']);
    foreach ($_SESSION['notifications'] as $key => $notification) {
        if ($notification['time'] == $time && $notificationCount == 1) {
            unset($_SESSION['notifications']);
        }
        if ($notification['time'] == $time ) {
            unset($_SESSION['notifications'][$key]);
        }

    }
}

echo "<script>location.reload();</script>";
?>