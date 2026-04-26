<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function getWeatherByCoordinates($lat, $lon) {
    
    $url = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&current_weather=true&hourly=temperature_2m,relativehumidity_2m,precipitation&timezone=Asia/Jakarta";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return ['error' => 'Gagal mengambil data cuaca'];
    }
    return json_decode($response, true);
}

$lat = $_GET['lat'] ?? '';
$lon = $_GET['lon'] ?? '';

if (empty($lat) || empty($lon)) {
    echo json_encode(['error' => 'Parameter latitude dan longitude wajib diisi']);
    exit;
}

$weather = getWeatherByCoordinates($lat, $lon);
echo json_encode($weather);
?>