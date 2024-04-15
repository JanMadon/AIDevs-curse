<?php

namespace app\Tasks;

use app\Answer\Answer;

class OwnApiProCall
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
        $response = $task->get('ownapipro');
        print_r($response);
     
         // wyślij url
         $res = Answer::answer($response['token'], "https://janmdev.pl/");
         print_r($res);
    }

    public function main()
    {
        
    }



}