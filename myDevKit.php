<?php
$config = require_once('conf.php');

require_once 'taskBloger.php';

if(!isset($argv[1])){
    exit("'getTask' or 'answer'");
}


if ($argv[1] == 'getTask') {
// zadania api: moderation,blogger
    $token = getToken('liar', $config)->token;
    $task = getTask($token);
    print_r($task);
    print_r($token);

} elseif ($argv[1] == 'answer') {
    $token = "06b7ca6b23612dc5974bfca66a0a770741b4a2db";
    $system = 'jesteś pomocnikiem blogera który piszę na temat przyrządzania pizzy Margherity, bloger poda ci nazwę rozdziału a ty mu ten rozdził napiszesz';

    $answer = [
        message($system, "Wstęp: kilka słów na temat historii pizzy", $config),
        message($system, "Niezbędne składniki na pizzę", $config),
        message($system, "Robienie pizzy", $config),
        message($system, "Pieczenie pizzy w piekarniku", $config),
    ];

    $response = answer($token, $answer);
   var_dump($response);
}


function getToken($task , $config)
{

    $url = "https://tasks.aidevs.pl/token/$task";

    $jsonData = json_encode([
        "apikey" => $config['apikey-aidevs']
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

    return json_decode(curl_exec($curl));
}

function getTask($token)
{
    $url = "https://tasks.aidevs.pl/task/$token";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $response = json_decode($response, true); // true dodaje tablice
    return $response;
}

function answer($token, $answer)
{
    $url = "https://tasks.aidevs.pl/answer/$token";

    $jsonData = json_encode([
        "answer" => $answer
    ]);
    print_r($answer);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);

    return json_decode(curl_exec($curl), true);
}
