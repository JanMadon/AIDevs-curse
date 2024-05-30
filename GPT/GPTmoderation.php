<?php

namespace app\GPT;

class GPTmoderation
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }


    function prompt($phrase)
    {
        $payload = [
           'input' => $phrase
        ];

        $curl = curl_init('https://api.openai.com/v1/moderations');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->conf['API_KEY_OPENAI']
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        curl_close($curl);
      
        $response = json_decode($response);

        return $response->results[0]->flagged;
    }

}