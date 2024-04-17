<?php

$config = require_once('../conf.php');
require_once __DIR__ . '/../vendor/autoload.php';

use app\Prompts\GPTprompt;

$json = file_get_contents('php://input');

$data = json_decode($json, true);
$question = $data["question"];

$chat = new GPTprompt($config);
$answer = $chat->message("Użytkownik zada Ci pytanie, jeśli bedzie ono wykraczać poza twoją wiedzę zwróć JSON z textem jaki powinienem wyszukać w google 
###\n
np: User pyta: Jaka bedzie jutro pogoda w Krakowie? \n
Ty odpowiadasz: 
{
    needMore: true,
    search: pogoda kraków jutro
}
wstaw cudzysłowia w odpowiednie miejsca aby otrzymać JSON
", $question);

$googleQuery = json_decode($answer)->search;

// zapytaj google:
$client = new \GoogleSearchResults("1f2b8d12ffdf7482b31533c255a247b907ec9b0fb2bef4b46143a370ea618221");
$query = ["q" => "$googleQuery", "gl" => "pl", "num" => 3];
$response = $client->get_json($query);
$response = $response->organic_results;
$jsonRes = json_encode($response);


$system = "Użytkownik zada ci pytanie odpowiedz na je (zwracając sam adres url)  mająć dane z wyszukiwarki google: $jsonRes 
###\n
Odpowiedz zwracając sam url.
";

$answer = $chat->message($system, $question);



$payLoad = [
    "reply" => extractUrl($answer),
];

header('Content-Type: application/json');
echo json_encode($payLoad);


////////////////////////////////////////////////////////
function extractUrl($sentence)
{
    // Wyrażenie regularne do znalezienia adresu URL
    $pattern = '/\b(?:https?|ftp):\/\/\S+\b/i';


    if (preg_match($pattern, $sentence, $matches)) {
        return $matches[0];
    } else {
        return "";
    }
}
