<?php
require_once('db.php');
require_once('config.php');

$isAuth = 0;

function UserExit() {
    $IdUserSession = $_SESSION['IdUserSession'];
    $sql = "delete from users_auth where hash_cookie = '$IdUserSession'";
    executeQuery($sql);

    unset($_SESSION['id_user']);
    unset($_SESSION['IdUserSession']);
    unset($_SESSION['login']);
    unset($_SESSION['pass']);

    setcookie('IdUserCookie', '', time() - 3600 * 24 * 7);

    return $isAuth = 0;
}

?>