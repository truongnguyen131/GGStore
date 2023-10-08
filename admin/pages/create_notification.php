<?php
function createNotification($message, $type,$time,$status)
{
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = array();
    }
    $_SESSION['notifications'][] = array(
        'message' => $message,
        'type' => $type,
        'time' => $time,
        'status' => $status
    );
}



?>