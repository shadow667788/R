<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

$number = $_GET['number'] ?? '';

if (empty($number)) {
    echo json_encode(["success" => false, "error" => "Number parameter missing"]);
    exit;
}

// Clean number
$number = preg_replace('/[^0-9]/', '', $number);
if (substr($number, 0, 1) === '0') {
    $number = substr($number, 1);
}

$url = "https://amscript.xyz/PublicApi/Siminfo.php?number=" . urlencode($number);

// Use cURL
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        CURLOPT_FOLLOWLOCATION => true,
    ]);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($response === false || $error) {
        echo json_encode(["success" => false, "error" => "CURL Error: " . $error]);
        exit;
    }
} else {
    // Fallback
    $response = @file_get_contents($url);
    if ($response === false) {
        echo json_encode(["success" => false, "error" => "Failed to fetch data"]);
        exit;
    }
}

// Return the response
echo $response;
?>