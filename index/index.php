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

function authWithCredential($username, $password) {
    $isAuth = 0;

    $link = getConnection();
    $login = mysqli_real_escape_string($link, $username);
    $sql = "select id_user, login, pass from users where login = '$login'";
    $user_date = getRowResult($sql, $link);

    if ($user_date) {
        $passHash = $user_date['pass'];
        $id_user = $user_date['id_user'];
        $idUserCookie = microtime(true) . rand(100, 100000000);
        if (password_verify($password, $passHash)) {
            $_SESSION['id_user'] = $id_user;
            $_SESSION['IdUserSession'] = $idUserCookie;
            $sql = "insert into users_auth (id_user, hash_cookie, date, prim) values ('$id_user', '$idUserCookie', now(), '1241241241')";
            executeQuery($sql);
            $isAuth = 1;

            if ($_POST['rememberme']) {
                setcookie('idUserCookie', $idUserCookie, time() + 3600 * 24 * 7);
            }
        } else {
            UserExit()
        }

    } else {
        UserExit()
    }

    return $isAuth;
}

function checkAuthWithSession($IdUserSession) {
    $isAuth = 0;

    $link = getConnection();
    $hash_cookie = mysqli_real_escape_string($link, $IdUserSession);
    $sql = "select * from users_auth where hash_cookie = '$hash_cookie'";
    $user_date = getRowResult($sql, $link);

    if ($user_date) {
        $isAuth = 1;
        $_SESSION['IdUserSession'] = $IdUserSession;
    } else {
        $isAuth = 0;
        UserExit();
    }
}

function checkAuthWithCookie() {
    $isAuth = 0;

    $link = getConnection();
    $idUserCookie = $_COOKIE['idUserCookie'];
    $hash_cookie = mysqli_real_escape_string($link, $idUserSession);
    $sql = "select * from users_auth where hash_cookie = '$hash_cookie'";
    $user_date = getRowResult($sql, $link);

    if ($user_date) {
        checkAuthWithSession($idUserCookie);
        $isAuth = 1;
    } else {
        $isAuth = 0;
        UserExit();
    }
}

if ($_POST['SubmitLogin']) {
    $isAuth = authWithCredential($_POST['login'], $_POST['pass']);
} else if ($_SESSION['login']) {
    $isAuth = checkAuthWithSession();
} else {
    $isAuth = checkAuthWithCookie();
}

if ($_POST['ExitLogin']) {
    $isAuth = UserExit();
}

?>

<html>
<head>
    <meta charset="utf-8">
    <title>Document</title>
</head>

<body>

<?php

echo "<pre>";
print_r($_POST);
print_r($_SESSION);
print_r($_COOKIE);
echo "</pre>";
?>

<?php if (!$isAuth): ?>

<form action="index.php" method="post">
    <label for="login">Login</label><input type="text" id="login" name="login" /><br>
    <label for="pass">Password</label><input type="password" id="pass" name="pass" /><br>
    <label for="rememberme">Remember: </label><input type="checkbox" name="rememberme" id="rememberme" />
    <input type="submit" name="SubmitLogin" value="Log-in" /> <a href="/register/">Register</a>
</form>

<?php endif; ?>

<br>

<?php if ($isAuth): ?>

<form action="index.php" method="post">
    <p>We have authorized by login <?=$_SESSION['login'] ?></p>
    <input type="submit" name="ExitLogin" value="Exit" />
</form>

<?php endif; ?>

</body>
</html>