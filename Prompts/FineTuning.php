<?php

namespace app\Prompts;

use CURLFile;

class FineTuning
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    function sendFile($fileToFT)
    {
        //$fileToFT = 'aidevs_dane_ft1.jsonl';

        $postData = [
            'purpose' => 'fine-tune',
            'file' => new CURLFile($fileToFT)
        ];

        $curl = curl_init('https://api.openai.com/v1/files');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->conf['openAi-key']
        ]);

        $response = curl_exec($curl);
        echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        print_r(curl_getinfo($curl));
        curl_close($curl);
        var_dump($response);
        return $response;
    }

    function learn($file){
        //$file = "file-9DHMjRtUxIvQ94JJ79HhbKjq";

        $postData = [
            'training_file' => $file,
            'model' => 'gpt-3.5-turbo-0613'
        ];

        $curl = curl_init('https://api.openai.com/v1/fine_tuning/jobs');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->conf['openAi-key']
        ]);

        $response = curl_exec($curl);
        echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        curl_close($curl);
        return $response;
    }
}
