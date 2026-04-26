<?php
$servername = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port       = (int)(getenv('DB_PORT') ?: 4000);
$username   = getenv('DB_USER') ?: '';
$password   = getenv('DB_PASSWORD') ?: '';
$dbname     = getenv('DB_NAME') ?: 'DiGadhu';
$caCert     = getenv('DB_CA_CERT');

$conn = null;

if (!empty($caCert)) {
    
    $caFile = tempnam(sys_get_temp_dir(), 'tidb_ca_');
    if ($caFile !== false) {
        file_put_contents($caFile, $caCert);
        
        $conn = mysqli_init();
        if ($conn) {
            
            $conn->ssl_set(null, null, $caFile, null, null);
            
            if (!$conn->real_connect($servername, $username, $password, $dbname, $port, null, MYSQLI_CLIENT_SSL)) {
                error_log("Koneksi SSL gagal: " . $conn->connect_error);
                $conn = null;
            } else {
                $conn->set_charset("utf8mb4");
                error_log("Koneksi ke TiDB Cloud BERHASIL dengan SSL");
            }
        }
        
    } else {
        error_log("Gagal membuat file temporary untuk CA certificate");
    }
}

if ($conn === null) {
    error_log("Mencoba koneksi tanpa SSL (hanya untuk debug)");
    $conn = @new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        error_log("Koneksi tanpa SSL gagal: " . $conn->connect_error);
        $conn = null;
    } else {
        $conn->set_charset("utf8mb4");
    }
}

?>
