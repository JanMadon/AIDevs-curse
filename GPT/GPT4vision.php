<?php

namespace app\GPT;

class GPT4vision
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    function prompt($user, $url)
    {
        $payload = [
            'model' => 'gpt-4-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            "type" => "text",
                            "text" => $user,
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => $url
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $payload = json_encode($payload);
        $curl = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($curl, CURLOPT_POST, true); // podobnu usi być true bo inaczej bedzie GET
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
        var_dump($response);
        return $response;
    }
}
