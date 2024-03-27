<?php
$config = require_once('conf.php');

//$data = [
//    'question' => 'What is capitol of poland?',
//];
//
//$url = "https://tasks.aidevs.pl/task/9cc3073ec48f1f6768fda656cfd40c1d0ea5a842";
//$curl = curl_init();
//curl_setopt($curl, CURLOPT_URL, $url);
//curl_setopt($curl, CURLOPT_POST, true);
//curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//$response = curl_exec($curl);
////$response = json_decode($response, true); // true dodaje tablice
//print_r($response);
//
//exit();

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


    $response = answer('9cc3073ec48f1f6768fda656cfd40c1d0ea5a842', "YES");
  // var_dump($response);
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

