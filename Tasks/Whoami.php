<?php

namespace app\Tasks;

use app\Answer\Answer;
use app\Prompts\GPTpromptContects;

class Whoami
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
        $prompt = new GPTpromptContects($this->conf);

        $i = 0;

        do {
            $i++;

            $response = $task->get('whoami');
            print_r($response);
            print_r($response['task']['hint']);

            // jesli uda mu się odgadnąć wyjdze z pentli wcześniej
            // ma maksymalnie 4 podejscia.
            //if($i<6){
                $system = 'Uzytkownik bedzie grał z tabą w grę whoami (bedzie podawał ciekawostki o osobie a twoim adaniem bedzie odgadniecie kto to).
                    Jesli bedziesz pewny na 95% o kogo chodzi, zwróć tę osobę.
                    Jeśli nie to zwróć false.
                
                    Dane zwracaj w postcji json :
                    np:
                        {
                            "ikow": 0
                        }
                    lub:
                        {
                            "ikow": 1,
                            "who": "Mike Tason"
                        }
              // Pamietaj to bardzo ważne udziel odpowiedzi jedynie gdy bedziesz pewnien na 95%';
            // } else {
            //     $system = 'Uzytkownik bedzie grał z tabą w grę whoami (bedzie podawał ciekawostki o osobie a twoim adaniem bedzie odgadniecie kto to).
            //             Dane zwracaj w postcji json :
            //             {
            //                 "ikow": 1,
            //                 "who": "Name Surname"
            //             }';
            // }


            if (isset($answer['inow'])) {
                $conversation[] = [
                    [
                        'role' => 'user',
                        'content' => $response['tesk']['hint']
                    ],
                    [
                        'role' => 'assistant',
                        'content' => $answer['inow'],
                    ]
                ];
            } else {
                $conversation[] = [
                    [
                        'role' => 'user',
                        'content' => $response['task']['hint']
                    ]
                ];
            }

            $res = $prompt->message($system,  ...$conversation);
            print_r($res);
            //exit();

            

            if ($i >= 10) {
                break;
            }

        } while (!$res["ikow"]);

        print_r('Ilość wskazówek to: ' . $i . "\n");

        // answer
        $res = Answer::answer($response['token'], $res['who']);
        print_r($res);
    }
}
