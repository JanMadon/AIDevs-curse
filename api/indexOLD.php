<?php

$config = require_once('../conf.php');
require_once __DIR__ . '/../vendor/autoload.php';

use app\Prompts\GPTprompt;

$json = file_get_contents('php://input');

$data = json_decode($json, true);
$question = $data["question"];

$chat = new GPTprompt($config);
$answer = $chat->message('Odpowiadaj krótko i tresciwie', $question);

$payLoad = [
    "reply" => $answer,
];



header('Content-Type: application/json');
echo json_encode($payLoad);
// echo "<br>";
// echo json_encode('jesteś tutaj!');

