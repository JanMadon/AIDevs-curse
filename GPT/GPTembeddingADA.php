<?php

namespace app\GPT;

// Embedding to reprezentacja tekstu w postaci wektora liczbowego, która pozwala modelowi zrozumieć i operować na danych językowych. W kontekście modelu text-embedding-ada-002 OpenAI, embeddingi są używane do mapowania słów, fraz lub dokumentów na wektory w przestrzeni wielowymiarowej, gdzie semantycznie podobne teksty mają podobne reprezentacje. Dzięki temu można efektywnie przeprowadzać zadania takie jak klasyfikacja, wyszukiwanie informacji czy analiza sentymentu.

// https://platform.openai.com/docs/guides/embeddings
class GPTembeddingADA
{
    public function __construct(private array $conf)
    {
    }


    function prompt($input): array
    {
        $payload = [
            'model' => 'text-embedding-ada-002',
            'encoding_format' => 'float',
            'input' => json_encode($input)
        ];

        $payload = json_encode($payload);
        $curl = curl_init('https://api.openai.com/v1/embeddings');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: Bearer ' . $this->conf['API_KEY_OPENAI']
        ]);

        if(curl_error($curl)) {
            // throw ...
        }

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response)->data[0]->embedding;

        return (array) $response;
    }

}