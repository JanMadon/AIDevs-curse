<?php

namespace app\Answer;
class Answer
{

    public static function answer($token, $answer)
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
//    curl_setopt($curl, CURLOPT_HTTPHEADER, [
//        'Content-Type: application/json',
//        'Content-Length: ' . strlen($jsonData)
//    ]);

        return json_decode(curl_exec($curl), true);
    }
}