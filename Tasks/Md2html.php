<?php

namespace app\Tasks;

use app\Tasks;
use app\Prompts\GPTprompt;
use app\Answer\Answer;
use app\Prompts\FineTuning;
use GoogleSearchResults;

class Md2html
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
        //pobierz zadanie:
        $task = new Task($this->conf);
        $response = $task->get('md2html');
        print_r($response);


        // wyślij plik do fine-tiningu 

        // $ft = new FineTuning($this->conf);
        // $response = $ft->sendFile('md-to-html.jsonl');
        // print_r($response);
        // exit();

        // ucz model:
        // $ft = new FineTuning($this->conf);
        // $response = $ft->learn('file-HwH9XWexG92RCNp7MakAA8MK');
        // print_r($response);
        // exit();

        // użyj modelu
        $gpt = new FineTuning($this->conf);
        $system = "przekształć dane z formatu Markdown na html, nie dodawaj żadnych komętaży od siebie";

        $answer = $gpt->message($system, $response['task']['input']);


        // prześlij adres api
        $res = Answer::answer($response['token'], $answer);
        print_r($res);
        
    

    }
}
