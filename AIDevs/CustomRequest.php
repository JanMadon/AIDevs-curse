<?php

namespace app\AIDevs;

class CustomRequest
{

    public function __construct(
        public string $url = "https://tasks.aidevs.pl",
    ) {
    }


    public function post($endpoint, $payload, $isJson = false)
    {
        $url = $this->url . '/' . $endpoint;

        if($isJson){
            $payload = json_encode($payload);
        }

        return $this->makeRequest($url, 'post', $payload);
    }

    public function get($endpoint)
    {
        $url = $this->url . '/' . $endpoint;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        
        return $this->makeRequest($url, "get");
    }

    private function makeRequest(string $url, string $method, array|string $payload = '', $isJson = false)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if($isJson){
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]); 
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: multipart/form-data',
            ]);
        }

        if ($method === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        }

        if(curl_exec($curl)){
            //error handling and log    
        };

        curl_close($curl);
        $response = json_decode(curl_exec($curl));
        return $response;
    }
}
