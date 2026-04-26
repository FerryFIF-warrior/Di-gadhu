<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$level = $_GET['level'] ?? 'prov';
$kode = $_GET['kode'] ?? '';

function getWilayah($level, $kode = '') {
    $baseUrl = "https://www.emsifa.com/api-wilayah-indonesia/api/";
    
    switch ($level) {
        case 'prov':
            $url = $baseUrl . "provinces.json";
            break;
        case 'kab':
            if (empty($kode)) return [];
            $url = $baseUrl . "regencies/" . $kode . ".json";
            break;
        case 'kec':
            if (empty($kode)) return [];
            $url = $baseUrl . "districts/" . $kode . ".json";
            break;
        default:
            return [];
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return [];
    }
    
    $data = json_decode($response, true);
    // Pastikan mengembalikan array
    return is_array($data) ? $data : [];
}

$data = getWilayah($level, $kode);
echo json_encode($data);
?>