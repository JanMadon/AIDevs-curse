<?php

namespace app\GPT;


// Model Whisper-1 od OpenAI to system przetwarzania mowy, który konwertuje mowę na tekst. Działa on w następujący sposób:

// Transkrypcja Mowy: Przekształca dźwięk mowy na tekst pisany.
// Rozpoznawanie Języka: Automatycznie rozpoznaje i transkrybuje różne języki.
// Szerokie Zastosowanie: Może być używany w aplikacjach takich jak automatyczne napisy, asystenci głosowi, notatki głosowe, itd.
// Whisper-1 jest zaprojektowany do dokładnego i skutecznego rozpoznawania mowy, nawet w trudnych warunkach akustycznych.

// https://platform.openai.com/docs/guides/speech-to-text
class GPTwhisper
{
    /**
     * @param $conf
     */
    public function __construct(private array $conf)
    {
    }

    function prompt($filePath)
    {
        $model = 'whisper-1';
        $payload = [
            'model' => $model,
        ];

        $postFields = [
            'file' => new \CURLFile($filePath),
            'model' => $model,
            'response_format' => 'text'
        ];

        $payload = json_encode($payload);
        $curl = curl_init('https://api.openai.com/v1/audio/transcriptions');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data',
            'Authorization: Bearer ' . $this->conf['API_KEY_OPENAI']
        ]);

        if(curl_error($curl)){
            // throw...
            echo curl_error($curl) ? 'Curl error: ' . curl_error($curl) : '';
        }

        $response = curl_exec($curl);
        curl_close($curl);
        // zwróci stringa
        return $response;
    }
}
