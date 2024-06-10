<?php

namespace app\AIDevs;

class CustomRequest
{

    private $setopt;

    public function __construct(
        public string $url = "https://tasks.aidevs.pl",
    ) {
    }


    public function post($endpoint, $payload, $isJson = false)
    {
        if($endpoint){
            $url = $this->url . '/' . $endpoint;
        } else {
            $url = $this->url;
        }

        if($isJson){
            $payload = json_encode($payload);
        }

        return $this->makeRequest($url, 'post', $payload);
    }

    public function get($endpoint, $isResponseJson = null)
    {
        if($endpoint){
            $url = $this->url . '/' . $endpoint;
        } else {
            $url = $this->url;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        
        return $this->makeRequest($url, "get", '', false, $isResponseJson);
    }

    public function addSetopt(array $setopt)
    {
        $this->setopt = $setopt;
    } 

    private function makeRequest(string $url, string $method, array|string $payload = '', $isJson = false, $isReturnJson = false)
    {
        try{
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if($this->setopt){
                foreach($this->setopt as $key => $value){
                    curl_setopt($curl, $key, $value);
                }
            }

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

            $executed = curl_exec($curl);
            if(curl_error($curl)){
                print_r('ERROR: '.curl_error($curl));    
            };
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
            curl_close($curl);

            

            if(json_decode($executed)){
                if($isReturnJson){
                    return json_decode($executed, true);
                }
               return json_decode($executed);
            }

            if($httpCode === 500){
                return false;
            }

            return $executed;
        } catch(\Exception $error) {
            print_r($error->getMessage());
        }
        
    }

    
}
