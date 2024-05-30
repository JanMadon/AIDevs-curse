<?php

namespace app\GPT;

class GPTpromptConversation
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }


    function message($system, $user)
    {
        file_put_contents('conversation.txt',  "U: $user \n", FILE_APPEND);

        //Sprawdz co jest w pliku
        $file = fopen('conversation.txt', "r");

        $conversation = [];

        if ($file) {
            while (($line = fgets($file)) !== false) {

                if (strpos($line, 'U:') === 0) {
                    $conversation[] = [
                        'role' => 'user',
                        'content' => substr($line, 3)
                    ];
                }

                if (strpos($line, 'A:') === 0) {
                    $conversation[] = [
                        'role' => 'assistant',
                        'content' => substr($line, 3)
                    ];
                }
            }

            fclose($file);

        } else {
            echo "Nie udało się otworzyć pliku.";
        }

        $model = 'gpt-4';
        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system
                ],
                ...$conversation
            ]
        ];


        $payload = json_encode($payload);
        $curl = curl_init('https://api.openai.com/v1/chat/completions');
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

        $response = json_decode($response)->choices;
        $response = (string)$response[0]->message->content;

        // zapisz odpowiedz w pliku
        file_put_contents('conversation.txt',  "A: $response \n", FILE_APPEND);

        return $response;
    }
}
