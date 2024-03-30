<?php

require_once 'Task.php';
require_once 'GPTpromptADA.php';
require_once 'Answer.php';

class Embedding
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
        $response = $task->get('embedding');

        // zapytaj chat o imie
        $prompt = new GPTpromptADA($this->conf);
        $array = $prompt->message('Hawaiian pizza');

        // answer
        $res = Answer::answer($response['token'], $array);
        print_r($res);
    }

}