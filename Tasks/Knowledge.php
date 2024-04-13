<?php

namespace app\Tasks;

use app\Answer\Answer;
use app\Prompts\GPTprompt;
use app\Prompts\GPTpromptADA;

class Knowledge
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
        $response = $task->get('knowledge');
        print_r($response);
        $type = $this->distributor($response['task']['question']);
        print_r($type);

        if($type == "cash"){
            $answer = $this->cash($response['task']['question']);
        } 

        if($type == "people"){
            $answer = $this->people($response['task']['question']);
        } 

        if($type == "general"){
            $answer = $this->people($response['task']['question']);
        } 

        // wyślij odpowiedz do AiDevs
        $res = Answer::answer($response['token'], $answer);
        print_r($res);
    }

    private function distributor($question)
    {
        $chat = new GPTprompt($this->conf);

        $sytem = "User zada Ci losowe pytanie na temat kursu walut, populacji wybranego kraju lub wiedzy ogólnej. Twoim zadaniem jest wybór odpowiedniego narzędzia. \n
        ### \n

        Odpowiedz jednym słowem: \n
        cash <- jeśli temat dotyczy kursu walut \n
        people <- jeśli temat dotyczy populacji \n
        general <- jeśli temat dotyczy wiedzy ogólnej \n 
        ";

        $chat = new GPTprompt($this->conf);
        $answer = $chat->message($sytem, $question);
        return $answer;
    }

    private function cash($question)
    {
        $chat = new GPTprompt($this->conf);

        $sytem = "User zada ci pytanie odnoscnie jakiejś waluty dwoim zadaniem jest zwrócenie kodu ISO 4217 tej waluty. Pamiętaj zawracasz tylko i wyłącznie kod!";

        $chat = new GPTprompt($this->conf);
        $code = $chat->message($sytem, $question);
        
        $curl = curl_init("http://api.nbp.pl/api/exchangerates/rates/a/$code/?format=json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      
        $response = json_decode(curl_exec($curl));
        print_r(curl_error($curl));
        curl_close($curl);
        
        return $response->rates[0]->mid;
    }

    private function people($question)
    {
        $chat = new GPTprompt($this->conf);

        $sytem = "User zada ci pytanie odnoscnie populacji w danym kraju, twoim zadaniem jest zwrócenie kodu danego kraju. \n
        ### \n
        np: 'podaj populację Francji' -zwracasz-> fr
        ### \n
         Pamiętaj zawracasz tylko i wyłącznie kod!";

        $chat = new GPTprompt($this->conf);
        $code = $chat->message($sytem, $question);
        
        $curl = curl_init("https://restcountries.com/v3.1/alpha/$code");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      
        $response = json_decode(curl_exec($curl));
        print_r(curl_error($curl));
        curl_close($curl);
        return $response[0]->population;
    }

    private function general($question)
    {
        $chat = new GPTprompt($this->conf);

        $sytem = "User zada ci pytanie. Odpowiedz krótko ale tresciwie wykorzystując swoją wiedzę";

        $chat = new GPTprompt($this->conf);
        $answer = $chat->message($sytem, $question);
        
        return $answer;
    }
}
