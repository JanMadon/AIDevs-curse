<?php

require_once 'Task.php';
require_once 'GPTpromptSpeech.php';
require_once 'Answer.php';

class Whisper
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
        $response = $task->get('whisper');


        //$fileUrl = substr($response['task']['msg'], strpos($response['task']['msg'], 'https'));
            // w ten sposób nie dziła pobieranie - pobrałem plik ręcznie.
        //$record = file_put_contents('mateusz.mp3', $fileUrl);

        // zapytaj chat o imie
        $prompt = new GPTpromptSpeech($this->conf);
        $text = $prompt->message('./mateusz.mp3');
        print_r($text);
        // answer
        $res = Answer::answer($response['token'], $text);
        print_r($res);
    }

}