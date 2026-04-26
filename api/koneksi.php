<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digadhu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>