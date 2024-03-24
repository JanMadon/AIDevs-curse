<?php
if(!isset($argv[1])){
    exit("'getTask' or 'answer'");
}


if ($argv[1] == 'getTask') {

    $token = getToken('moderation')->token;
    $task = getTask($token);
    print_r($task);
    print_r($token);

} elseif ($argv[1] == 'answer') {
    $token = "4f847e473c1630eacd9335ededec3a195157438a";

    $answer = [0, 1, 0, 1];

    $response = answer($token, $answer);
   var_dump($response);
}


function getToken($task)
{
    $url = "https://tasks.aidevs.pl/token/$task";

    $jsonData = json_encode([
        "apikey" => "af693b93-4488-4f7a-811e-c0910ac17ba4"
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
    exit();
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
