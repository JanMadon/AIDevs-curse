<?php
//$config = require_once('conf.php');
//message('jesteś pomocnikiem', 'hej!', $config);

function message($system, $user, $conf)
{
    $model = 'gpt-3.5-turbo';
    $payload = [
        'model' => $model,
        'messages' => [
            [
                'role' => 'system',
                'content' => $system
            ],
            [
                'role' => 'user',
                'content' => $user
            ]
        ]
    ];

    $payload = json_encode($payload);
    $curl = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($curl, CURLOPT_POST, true); // podobnu usi być true bo inaczej bedzie GET
    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload),
        'Authorization: Bearer ' . $conf['openAi-key']
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
    curl_close($curl);

    $response = json_decode($response)->choices;
    $response = (string) $response[0]->message->content;
    var_dump($response);
    return $response;
}


