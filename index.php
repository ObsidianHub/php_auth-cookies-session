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