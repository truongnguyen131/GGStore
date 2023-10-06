<?php
session_start();
session_destroy();
if (isset($_COOKIE['rememberMe']) &&  $_COOKIE['rememberMe'] == "unchecked") {
    unset($_COOKIE['username']);
    unset($_COOKIE['password']);
    unset($_COOKIE['rememberMe']);
}
header("location: ../home");
?>