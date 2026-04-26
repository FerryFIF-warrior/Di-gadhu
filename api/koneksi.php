<?php

$servername = getenv('db_host') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = (int)(getenv('db_port') ?: 4000);
$username = getenv('db_user') ?: '4B6utpLvXenVLykroot';
$password = getenv('db_password') ?: 'LhQqZDL6qAx8pf';
$dbname = getenv('db_name') ?: 'DiGadhu';
$caCert = getenv('db_ca_cert');

$conn = null;

if (empty($caCert)) {
    error_log("BD_CA_CERT environment variable not set");
} else {
    $caFile = tempnam(sys_get_temp_dir(), 'tidb_ca_');
    if ($caFile === false) {
        error_log("Failed to create temp file for CA cert");
    } else {
        file_put_contents($caFile, $caCert);

        $conn = new mysqli();
        $conn->init();

        $conn->ssl_set(null,null,$cafile,null,null);

        if (!$conn->real_connect($servername, $username, $password, $dbname, $port, null, MYSQLI_CLIENT_SSL)) {
            error_log("Koneksi DB gagal: ".$conn->connect_error);
            $conn = null;
        } else {
            $conn->set_charset("utf8mb4");
        }
    }
}

$conn->set_charset("utf8mb4");
?>
