<?php

namespace app\Tasks;

use app\Tasks;
use app\Prompts\GPTprompt;
use app\Answer\Answer;
use GoogleSearchResults;

class Google
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
        $response = $task->get('google');
        var_dump($response);

        // prześlij adres api
        $res = Answer::answer($response['token'], "https://janmdev.pl/");
        print_r($res);
        exit();
        
        
        /*
        $client = new GoogleSearchResults($this->conf['serpapi-key']);

        $query = ["q" => "Szukam adresu strony na której mogę się zapisać na newsletter prowadzony przez Jakuba Mrugalskiego. To jakiś newsletter technologiczny, ale nie pamietam nazwy", "gl" => "pl"];
        $response = $client->get_json($query);
        print_r($response->organic_results[1]);
        */
    
    }
}
