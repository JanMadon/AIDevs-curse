<?php

class GPTpromptSpeech
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }


    function message($filePath)
    {
        $model = 'whisper-1';
        $payload = [
            'model' => $model,
        ];

        $postFields = [
            'file' => new CURLFile($filePath),
            'model' => $model,
            'response_format' => 'text'
        ];

        $payload = json_encode($payload);
        $curl = curl_init('https://api.openai.com/v1/audio/transcriptions');
        curl_setopt($curl, CURLOPT_POST, true); // podobnu usi być true bo inaczej bedzie GET
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data',
            'Authorization: Bearer ' . $this->conf['openAi-key']
        ]);

        $response = curl_exec($curl);
        echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        curl_close($curl);

        return $response;
    }


}