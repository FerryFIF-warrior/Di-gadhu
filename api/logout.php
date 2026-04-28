<?php
require_once 'koneksi.php';
session_set_cookie_params([
                          'path' => '/',
                          'domain' => '.vercel.app',
                          'secure' => true,
                          'httponly' => true,
                          'samesite' => 'Lax'
]);
session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}

session_destroy();

setcookie("user_login", "", time() - 3600, "/");

header("Location: login.php");
exit();
?>
