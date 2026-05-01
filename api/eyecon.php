<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('X-Powered-By: WASIF ALI');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if number parameter exists
if (!isset($_GET['number']) || empty(trim($_GET['number']))) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'code' => 400,
        'message' => 'Number parameter is required!',
        'example' => '?number=+923001234567',
        'developer' => [
            'name' => 'WASIF ALI',
            'telegram' => '@FREEHACKS95',
            'channel' => '@THE_FREE_HACKS'
        ]
    ], JSON_PRETTY_PRINT);
    exit;
}

$number = trim($_GET['number']);

// Validate number format (must start with + followed by country code)
if (!preg_match('/^\+[1-9]\d{7,14}$/', $number)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'code' => 400,
        'message' => 'Invalid number format! Number must include country code starting with +',
        'example' => '?number=+923001234567',
        'your_input' => $number,
        'developer' => [
            'name' => 'WASIF ALI',
            'telegram' => '@FREEHACKS95',
            'channel' => '@THE_FREE_HACKS'
        ]
    ], JSON_PRETTY_PRINT);
    exit;
}

$A = 'true';
$url = 'https://api.eyecon-app.com/app/getnames.jsp';

$params = [
    'cli' => $number,
    'lang' => 'en',
    'is_callerid' => $A,
    'is_ic' => $A,
    'cv' => 'vc_672_vn_4.2025.10.17.1932_a',
    'requestApi' => 'URLconnection',
    'source' => 'MenifaFragment'
];

$headers = [
    'accept: application/json',
    'e-auth-v: e1',
    'e-auth: c5f7d3f2-e7b0-4b42-aac0-07746f095d38',
    'e-auth-c: 40',
    'e-auth-k: PgdtSBeR0MumR7fO',
    'accept-charset: UTF-8',
    'content-type: application/x-www-form-urlencoded; charset=utf-8',
    'User-Agent: EyeconApp/4.5.2 (Android 12; SDK 31)'
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url . '?' . http_build_query($params),
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 25,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_FOLLOWLOCATION => true
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
curl_close($ch);

// Format response time
$response_time = round($total_time * 1000, 2) . 'ms';

if ($curl_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'code' => 500,
        'message' => 'Failed to connect to Eyecon API',
        'error_details' => $curl_error,
        'developer' => [
            'name' => 'WASIF ALI',
            'telegram' => '@FREEHACKS95',
            'channel' => '@THE_FREE_HACKS'
        ]
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($http_code !== 200 || !$response) {
    http_response_code(502);
    echo json_encode([
        'status' => 'error',
        'code' => 502,
        'message' => 'Eyecon API returned error',
        'http_code' => $http_code,
        'developer' => [
            'name' => 'WASIF ALI',
            'telegram' => '@FREEHACKS95',
            'channel' => '@THE_FREE_HACKS'
        ]
    ], JSON_PRETTY_PRINT);
    exit;
}

// Decode and format the Eyecon API response
$eyecon_data = json_decode($response, true);

// Build professional response
$formatted_response = [
    'status' => 'success',
    'code' => 200,
    'timestamp' => date('Y-m-d H:i:s'),
    'response_time' => $response_time,
    'query' => [
        'number' => $number,
        'country_code' => substr($number, 0, 3)
    ],
    'data' => $eyecon_data,
    'developer' => [
        'name' => 'WASIF ALI',
        'telegram' => '@FREEHACKS95',
        'channel' => '@THE_FREE_HACKS'
    ]
];

http_response_code(200);
echo json_encode($formatted_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
