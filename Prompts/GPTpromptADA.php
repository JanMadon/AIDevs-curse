<?php

namespace app\Prompts;
class GPTpromptADA
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }


    function message($input)
    {

        $payload = [
            'model' => 'text-embedding-ada-002',
            'encoding_format' => 'float',
            'input' => json_encode($input)
        ];

        $payload = json_encode($payload);
        $curl = curl_init('https://api.openai.com/v1/embeddings');
        curl_setopt($curl, CURLOPT_POST, true); // podobnu usi być true bo inaczej bedzie GET
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: Bearer ' . $this->conf['openAi-key']
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        curl_close($curl);

        $response = json_decode($response)->data[0]->embedding;

        return (array)$response;
    }

}