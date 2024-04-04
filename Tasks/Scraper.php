<?php

namespace app\Tasks;

use app\Answer\Answer;
use app\Prompts\GPTprompt;
use Exception;

class Scraper
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
        $response = $task->get('scraper');
        print_r($response);

        // Pobierz text do zadnia:
        $text = (function($res) {

            try{

            $curl = curl_init($res['task']['input']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36');

            $respnse = curl_exec($curl);

            $error = curl_error($curl);
            curl_close($curl);
            
            print_r('response:' . $respnse);
            print_r('error:' . $error);

            } catch (Exception $e) {
                var_dump($e);
            } 

            return $respnse;

        })($response);

        exit();

        // zadaj pytanie do chatu
        $prompt = new GPTprompt($this->conf);
        $res = $prompt->message($response['task']['msg'] . '\n ### \n' . $text, $response['task']['question']);

        // answer
        $res = Answer::answer($response['token'], $res);
        print_r($res);
    }

}