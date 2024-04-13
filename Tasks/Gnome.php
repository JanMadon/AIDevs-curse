<?php

namespace app\Tasks;

use app\Answer\Answer;
use app\Prompts\GPT4vision;
use app\Prompts\GPTprompt;
use app\Prompts\GPTpromptADA;

class Gnome
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
        $response = $task->get('gnome');
        print_r($response);
        
        $visionGPT = new GPT4vision($this->conf);
        $answer = $visionGPT->message($response['task']['msg'], $response['task']['url']);
        // wyślij odpowiedz do AiDevs
        $res = Answer::answer($response['token'], $answer);
        print_r($res);
    }


}
