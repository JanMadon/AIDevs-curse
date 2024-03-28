<?php

require_once 'Task.php';
require_once 'GPTprompt.php';
require_once 'Answer.php';

class Inpromt
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
        $response = $task->get('inprompt');

        // zapytaj chat o imie
        $prompt = new GPTprompt($this->conf);
        $name = $prompt->message('zwróć tylko imię jakie wystąpi w zdaniu',$response['task']['question']);
        var_dump($response);

        // przeszukaj tablice wyników
        $sentences = [];
        foreach($response['task']['input'] as $sentence) {
            if(str_contains($sentence, $name)){
                $sentences[] = $sentence;
            }
        }

        // poproś chat o odpowiedz na pytanie
        $answer = $prompt->message($sentences[0],$response['task']['question']);

        // answer
        $res = Answer::answer($response['token'], $answer);
        print_r($res);
    }

}