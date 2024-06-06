<?php

namespace app\GPT;

/*
    Endpoint moderation w OpenAI służy do filtrowania i moderowania treści generowanych przez modele językowe. Główne zastosowania obejmują:

    * Zapobieganie Nieodpowiednim Treściom: Automatyczne wykrywanie i blokowanie treści, które są obraźliwe, niebezpieczne, lub naruszają zasady etyki i bezpieczeństwa.
    * Bezpieczeństwo Użytkowników: Ochrona użytkowników przed szkodliwymi lub nieodpowiednimi odpowiedziami ze strony modelu.
    * Zgodność z Regulacjami: Utrzymywanie zgodności z regulacjami prawnymi i standardami społecznymi dotyczącymi treści generowanych przez AI.
    Endpoint moderation jest kluczowym elementem zapewniającym, że modele AI działają w sposób odpowiedzialny i bezpieczny dla użytkowników.
*/

// https://platform.openai.com/docs/guides/moderation
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