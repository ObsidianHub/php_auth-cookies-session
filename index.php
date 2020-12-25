<?php
session_start();

$isAuth = 0;

$login = "user";
$pass = "qwerty";

function UserExit() {
    unset($_SESSION['login']);
    unset($_SESSION['pass']);
    setcookie('login', '', time() - 3600 * 7 * 24);
    setcookie('pass', '', time() - 3600 * 7 * 24);
    return $isAuth = 0;
}