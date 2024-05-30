<?php

namespace app\controllers;

use app\AIDevs\Answer;
use app\AIDevs\Task;
use app\core\Controller;

class TaskController extends Controller
{
    public function helloapi()
    {    
        $task = new Task($this->config);
        $apiRes = $task->get('helloapi');

        $token = $apiRes['token'];
        
        $answer = new Answer();
        $resAns = $answer->answer($token, $apiRes['task']['cookie']);

        dd($apiRes);
        dd($resAns);


        $param = [
            'name' => 'hello-api',
            'taskNr' => 'first',
        ];
        $this->view->main($param);
    }

    public function moderation()
    {
        $this->view->main();
    }

    public function blogger()
    {
        $this->view->main();
    }
}
