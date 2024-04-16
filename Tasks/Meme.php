<?php

namespace app\Tasks;

use app\Answer\Answer;
use app\Prompts\GPTpromptSpeech;
use app\Prompts\RenderForm;

class Meme
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
        $response = $task->get('meme');
        print_r($response);


        //$fileUrl = substr($response['task']['msg'], strpos($response['task']['msg'], 'https'));
        // w ten sposób nie dziła pobieranie - pobrałem plik ręcznie.
        //$record = file_put_contents('mateusz.mp3', $fileUrl);

        // zapytaj chat o imie
        $meme = new RenderForm($this->conf);
        $url = $meme->picture($response['task']['text'], $response['task']['image']);
        print_r($url);
        // answer
        $res = Answer::answer($response['token'], $url);
        print_r($res);
    }

}