<?php
$conf = require_once ('conf.php');

if ($argv[1] == 'getTask') {
    $url = "https://tasks.aidevs.pl/token/helloapi";

    $jsonData = json_encode([
        "apikey" => $conf['apikey']
    ]);

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData); // Ustawienie danych JSON jako treść zapytania
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json', // Ustawienie nagłówka Content-Type na application/json
        'Content-Length: ' . strlen($jsonData) // Ustawienie długości treści
    ]);

    $response = json_decode(curl_exec($curl));

    print_r($response);
    $token = $response->token;

    $url = "https://tasks.aidevs.pl/task/$token";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    $response = curl_exec($curl);
    print_r(json_decode($response));

} elseif ($argv[1] == 'answer' ) {
    $url = "https://tasks.aidevs.pl/answer/fda4bad86ad30d3221d20bd906ef72740ce7644b";

    $jsonData = json_encode([
        "answer" => "aidevs_20d2695e"
    ]);

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData); // Ustawienie danych JSON jako treść zapytania
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json', // Ustawienie nagłówka Content-Type na application/json
        'Content-Length: ' . strlen($jsonData) // Ustawienie długości treści
    ]);

    $response = json_decode(curl_exec($curl));

    print_r($response);
}