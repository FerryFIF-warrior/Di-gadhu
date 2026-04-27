<?php
$servername = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port       = (int)(getenv('DB_PORT') ?: 4000);
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASSWORD') ?: '';
$dbname     = getenv('DB_NAME') ?: 'DiGadhu';

$conn = null;
$conn = mysqli_init();
if ($conn) {
    
    $conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
    $flags = MYSQLI_CLIENT_SSL;
    if (!$conn->real_connect($servername, $username, $password, $dbname, $port, null, $flags)) {
        error_log("Koneksi database gagal: " . $conn->connect_error);
        $conn = null;
    } else {
        $conn->set_charset("utf8mb4");
        error_log("Koneksi ke TiDB Cloud BERHASIL (SSL)");
    }
}

if ($conn) {
    require_once __DIR__ . '/session_handler.php';
    $handler = new MySQLSessionHandler($conn);
    session_set_save_handler($handler, true);
}
?>
