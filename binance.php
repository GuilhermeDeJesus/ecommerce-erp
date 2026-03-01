<?php
date_default_timezone_set('America/Sao_Paulo');
$apiKey = "c4jDL57VJcJonjtj6nLocDRPmNuF3d71F2VGJMXXVRqkrCvLfg3vHxWczus8KGkx";
$secretKey = "IE6Rre5QWyUd9wSIOjfXLRqPetgupbOvsTMmF7WwGpTW6uiaEyLIUBFeMPHO4QXK";
$timestamp = "timestamp=" . time() * 1000;
$signature = hash_hmac('SHA256', $timestamp, $secretKey);

$url = "https://api.binance.com/sapi/v1/capital/withdraw/history?" . $timestamp . '&signature=' . $signature;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-MBX-APIKEY:' . $apiKey
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_URL, $url);

$response = curl_exec($ch);
$response = json_decode($response, true);

echo "<pre>";
print_r($response);

echo "<br><br>";
print_r($_SERVER);