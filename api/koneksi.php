<?php
$servername = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port       = (int)(getenv('DB_PORT') ?: 4000);
$username   = getenv('DB_USER') ?: '';
$password   = getenv('DB_PASSWORD') ?: '';
$dbname     = getenv('DB_NAME') ?: 'DiGadhu';

$conn = null;

$conn = mysqli_init();
if ($conn) {
    
    if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
        $conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
    }
    
    $flags = MYSQLI_CLIENT_SSL;
    if (defined('MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT')) {
        $flags = MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
    }
    
    if (!$conn->real_connect($servername, $username, $password, $dbname, $port, null, $flags)) {
        error_log("Koneksi SSL (skip verify) gagal: " . $conn->connect_error);
        $conn = null;
    } else {
        $conn->set_charset("utf8mb4");
        error_log("Koneksi ke TiDB Cloud BERHASIL (SSL skip verify)");
    }
}

if ($conn === null) {
    $conn = @new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        error_log("Koneksi tanpa SSL juga gagal: " . $conn->connect_error);
        $conn = null;
    } else {
        $conn->set_charset("utf8mb4");
    }
}

?>
