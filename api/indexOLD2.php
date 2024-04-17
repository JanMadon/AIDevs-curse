<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$config = require_once('../conf.php');
require_once __DIR__ . '/../vendor/autoload.php';

use app\Prompts\GPTpromptConversation;

$json = file_get_contents('php://input');

$data = json_decode($json, true);
$question = $data["question"];

$chat = new GPTpromptConversation($config);
$system = "User bedzie podawał ci dane o sobie, następnie może zadać ci pytanie dotyczące tych informacji. \n
Jeśli user zada pytanie zwróć odpowiedż, a jeśli jakiś informacje prowadż konwersację \n
Odpowiadaj krótko i treściwie.
";

$answer = $chat->message($system, $question);

$payLoad = [
    "reply" => $answer,
];

header('Content-Type: application/json');
echo json_encode($payLoad);


