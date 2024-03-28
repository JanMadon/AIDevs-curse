<?php


class Task
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    public function get($taskName)
    {

        $token = $this->getToken($taskName)->token;
        $task = $this->getTask($token);
        print_r($task);
        print_r($token);

        return [
            'task' => $task,
            'token' => $token
        ];
    }

    private function getToken($task)
    {
        $url = "https://tasks.aidevs.pl/token/$task";

        $jsonData = json_encode([
            "apikey" => $this->conf['apikey-aidevs']
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

    private function getTask($token)
    {
        $url = "https://tasks.aidevs.pl/task/$token";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $response = json_decode($response, true); // true dodaje tablice
        return $response;
    }

}




