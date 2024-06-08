<?php

namespace app\GPT;

class GPT35turbo
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    // if prompt give array => cerate convertation with them.
    function prompt(string $system, array|string $contents)
    {
        $userContent = [];
          
        if(is_string($contents)){
            $contents[] = [
                'role' => 'user',
                'content' => $contents
            ];
        } else {

        }
        
        
        $model = 'gpt-3.5-turbo';
        //$model = 'gpt-4';
        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system
                ],
                ...$contents
            ]
        ];

        $payload = json_encode($payload);

        $curl = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: Bearer ' . $this->conf['API_KEY_OPENAI']
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        curl_close($curl);

        $response = json_decode($response)->choices;
        $response = (string)$response[0]->message->content;

        // dd($response);
        // echo "..........";

        return $response;
    }
}
