<?php

namespace app\controllers;

use app\AIDevs\Answer;
use app\AIDevs\Task;
use app\core\Controller;
use app\GPT\GPTmoderation;

class TaskController extends Controller
{
    public function helloapi()
    {
        $task = new Task($this->config);
        $apiRes = $task->get('helloapi');

        $token = $apiRes['token'];

        $answer = new Answer();
        $ansRes = $answer->answer($token, $apiRes['task']['cookie']);

        $param = $this->prepareData($apiRes, $apiRes['task']['cookie'], $ansRes);

        $this->view->main($param);
    }

    public function moderation()
    {

        /*
            Zastosuj wiedzę na temat działania modułu do moderacji treści i rozwiąż zadanie o nazwie “moderation” z użyciem naszego API do sprawdzania rozwiązań. Zadanie polega na odebraniu tablicy zdań (4 sztuki), a następnie zwróceniu tablicy z informacją, które zdania nie przeszły moderacji. Jeśli moderacji nie przeszło pierwsze i ostatnie zdanie, to odpowiedź powinna brzmieć [1,0,0,1]. Pamiętaj, aby w polu ‘answer’ zwrócić tablicę w JSON, a nie czystego stringa.
            P.S. wykorzystaj najnowszą wersję modelu do moderacji (text-moderation-latest)
        */

        $task = new Task($this->config);
        $apiRes = $task->get('moderation');

        $token = $apiRes['token'];


        $gpt = new GPTmoderation($this->config);
        $ans = [];
        foreach ($apiRes['task']['input'] as $phrase) {
            $res = $gpt->prompt($phrase);
            if ($res) {
                array_push($ans, 1);
            } else {
                array_push($ans, 0);
            }
        }

        $answer = new Answer();
        $ansRes = $answer->answer($token, $ans);

        $param = $this->prepareData($apiRes, json_encode($ans) , $ansRes);

        $this->view->main($param);
    }

    public function blogger()
    {
        $this->view->main();
    }
}
