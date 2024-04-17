<?php

namespace app\Tasks;

use app\Tasks;
use app\Prompts\GPTprompt;
use app\Answer\Answer;


class Optimaldb
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    public function run()
    {
        $task = new Task($this->conf);
        $response = $task->get('optimaldb');
        var_dump($response);

        // pobierz dane z endopintu:
        
        $curl = curl_init($response['task']['database']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $db = json_decode(curl_exec($curl), true);
        print_r($db);

        $system = "Twoim zadaniem jest skrócenie tekstu, jednocześnie zachowując sens zdania.

        !!!
        Zasady:
        1. Skróć tekst do około 1/3 pierwotnej długości.
        2. Usuń imię z wygenerowanej odpowiedzi.
        3. Zachowaj istotne informacje.
        4. Każde zdanie traktuj jako osobną rzecz do przetworzenia. Każda zdanie powinno być wypisane od myślnika już po przetworzeniu.
        
        ### Przykład ('U:' to user, 'A:' to odpowiedź)
        U: Podczas ostatniej konferencji technologicznej, program który stworzył Zygfryd wygrał nagrodę za innowacyjność w użyciu JavaScript.Wielu nie wie, ale ulubionym instrumentem muzycznym Zygfryda jest ukulele, na którym gra po nocach, kiedy kodowanie na dziś się skończy.
        A: Zygfryd: 
        
        - wygrał nagrodę za innowacyjność w użyciu JavaScript
        - ulubionym instrumentem muzyczny ukulele
        
        ###
        Weź głęboki wdech. Skup się na zasadzie 2.";

        
        $prompt = new GPTprompt($this->conf);
        $dbOpt1 = $prompt->message( $system, implode(". ",$db['zygfryd']));
        $dbOpt2 = $prompt->message( $system, implode(". ",$db['stefan']));
        $dbOpt3 = $prompt->message( $system, implode(". ",$db['ania']));

        print_r($dbOpt1 );

        $answer = 'Zygfryd:' . $dbOpt1 . "###\n" . 'stefan:' . $dbOpt2 . "###\n" . 'ania' . $dbOpt3 ;
        
        $response = $task->get('optimaldb');
        $res = Answer::answer($response['token'], $answer);
        print_r($res);
    }

    //  array_merge($db['zygfryd'], $db['stefan'], $db['ania'])
}
