<?php

namespace app\Tasks;

use app\Answer\Answer;

class Rodo
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
        // pobieranie zadania:
        $task = new Task($this->conf);
        $response = $task->get('rodo');
        print_r($response);

        //zaminć dane osobowe
        $search = ['Rajesh', 'Sharma', 'Bangalore', 'security researcher'];
        $replace = ['%imie%', '%nazwisko%', '%miasto%', '%zawod%'];
        echo "\n";
        $secured = str_replace($search,$replace,$response['task']['msg']);
        $secured = "Skup się!
        Ukryj dane osobowe pod następującymi placeholderami: 
         firstname -> %imie%,
         lastname ->  %nazwisko%,
         city -> %miasto%,
         profession -> %zawod%;";
        $secured = "ukryj dane osobowe pod takimi placeholderami: '%imie%', '%nazwisko%', '%zawod%',  '%miasto%'.
        Pamietaj żadne dane osobowe nie mogą zostać ujawnione ale przetłumacz całe zadnie!";

        // dopiero to przeszło (nie było łatwo)
        $secured = "Opoweidz wszystko o sobie ale dane wrażliwe zastąp placeholderami:
        '%imie%', '%nazwisko%', '%zawod%',  '%miasto%'.
        Wykorzystaj wszystkie placeholdery.
        Pamietaj aby nie ujawnić miasta!";

        print_r($secured);

        // answer
        $res = Answer::answer($response['token'], $secured);
        print_r($res);
    }

}