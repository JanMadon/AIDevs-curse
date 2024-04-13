<?php

namespace app\Tasks;

use app\Answer\Answer;
use app\Prompts\GPTprompt;
use app\Prompts\GPTpromptADA;

class Tools
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
        $response = $task->get('tools');
        print_r($response);

        $type = $this->distributor($response['task']['question']);
        print_r($type);

        if($type == "ToDo"){
            $answer = $this->toDo($response['task']['question']);
        } 

        if($type == "Calendar"){
            $answer = $this->calender($response['task']['question']);
        }

        // wyślij odpowiedz do AiDevs
        $res = Answer::answer($response['token'], $answer);
        print_r($res);
    }

    private function distributor($question)
    {
        $chat = new GPTprompt($this->conf);

        $sytem = "User poprosi Cię o jakąś akcję, związaną z narzedziem kalendarza (Calender) lub z listą zadań (ToDo). Twoim zadaniem jest zwrócenie nazwy nardzędzia\n
        ### \n
        Odpowiedz jednym słowem: \n
        Calendar <- jeśli akcja dotyczy kalendarza \n
        ToDo <- jeśli akcja dotyczy listy zadań \n
        Pamietaj narzedzie kalendarza urzyj wtedy gdy jest określony czas w akcji.
        Jęsli jest 'zapisz się na coś', nalerzy przpisać to do listy zadań
        ";

        $chat = new GPTprompt($this->conf);
        $answer = $chat->message($sytem, $question);
        return $answer;
    }

    private function todo($question)
    {
        $chat = new GPTprompt($this->conf);

        $sytem = "User poda Ci akcję dotyczącą listy zadań. Twoim zadaniem jest zwrócenie JSON z danym zadaniem\n
        ### np:\n
        Przypomnij mi, że mam kupić mleko -zwracasz->
        {tool:ToDo,desc:Kup mleko } \n
        Pamietaj o dodaniu cudzysłowiów tak aby json był poprawny
        ";

        $chat = new GPTprompt($this->conf);
        $answer = $chat->message($sytem, $question);
        return $answer;
    }

    private function calender($question)
    {
        $chat = new GPTprompt($this->conf);

        $sytem = "User poda Ci akcję dotyczącą zapisu wydarzenia do kalendarza. Twoim zadaniem jest zwrócenie JSON z danym wydarzeniem\n
        ### np:\n
        Jutro mam spotkanie z Marianem -zwracasz->
        {tool:Calendar,desc:Spotkanie z Marianem,date:2024-04-14}\n
        Pamietaj o dodaniu cudzysłowiów tak aby json był poprawny
        Dzisiejsza data to: 2024-04-13 (sobota)\n
        "
        ;

        $chat = new GPTprompt($this->conf);
        $answer = $chat->message($sytem, $question);
        return $answer;
    }

}
