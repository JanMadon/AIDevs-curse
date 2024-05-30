<?php

namespace app\AIDevs;

class Task
{
    public function __construct(private array $conf)
    {
    }

    public function get($taskName)
    {

        $token = $this->getToken($taskName)->token;
        $task = $this->getTask($token);

        return [
            'task' => $task,
            'token' => $token
        ];
    }

    private function getToken($task)
    {
        $url = "https://tasks.aidevs.pl/token/$task";

        $jsonData = json_encode([
            "apikey" => $this->conf['API_KEY_AIDEVS']
        ]);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        $response = json_decode(curl_exec($curl));

        return $response;
    }

    private function getTask($token)
    {
        $url = "https://tasks.aidevs.pl/task/$token";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        return $response;
    }

}




