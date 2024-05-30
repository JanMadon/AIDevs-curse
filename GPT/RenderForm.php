<?php

namespace app\GPT;

class RenderForm
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }


    function picture($myText, $myImage)
    {
        $payload = [
            "template" => "burly-kangaroos-jog-sadly-1982",
            "data" => [
                "title.color" => "#FFFFFF",
                "title.text" => $myText,
                "image.src" => "https://tasks.aidevs.pl/data/monkey.png"
            ]
        ];

        $payload = json_encode($payload);
        // print_r($payload);
        // exit();

        $curl = curl_init('https://get.renderform.io/api/v2/render');
        curl_setopt($curl, CURLOPT_POST, true); // podobnu usi być true bo inaczej bedzie GET
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-KEY: ' . $this->conf['render-form']
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        curl_close($curl);

        $response = json_decode($response);
        var_dump($response);
        return $response->href;
    }
}
