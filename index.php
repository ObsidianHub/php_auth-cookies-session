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

function authWithCredential($username, $password) {
    global $login;
    global $pass;
    $isAuth = 0;
    if ($username == $login && $password == $pass) {
        $isAuth = 1;
        $_SESSION['login'] = $username;
        $_SESSION['pass'] = $password;

        if ($_POST['rememberme']) {
            setcookie('login', $_POST['login'], time() + 3600 * 24 * 7);
            setcookie('pass', $_POST['pass'], time() + 3600 * 24 * 7);
        }
    } else {
        UserExit();
    }

    return $isAuth;
}

function checkAuthWithSession() {
    $isAuth = 0;
    global $pass;
    $username = $_SESSION['login'];

    if ($username) {
        if ($_SESSION['pass'] == $pass) {
            $isAuth = 1;
        } else {
            $isAuth = 0;
            UserExit();
        }
    }

    return $isAuth;
}