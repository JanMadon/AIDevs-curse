<?php

namespace app\Tasks;

use app\Answer\Answer;

class OwnApiCall
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
        $response = $task->get('ownapi');
        print_r($response);

         // answer
         $res = Answer::answer($response['token'], "https://janmdev.pl/");
         print_r($res);
    }

}