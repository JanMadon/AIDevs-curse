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
        $ansRes = $answer->answer($token, $apiRes['task']['cookie']);

        $param = $this->prepareData($apiRes, $apiRes['task']['cookie'], $ansRes);

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

    protected function prepareData($apiRes, $sentAns, $ansRes)
    {
        return [
            'Token' => $apiRes['token'],
            'Task msg.' => $apiRes['task']['msg'],
            'Answer' => $sentAns,
            'Results code' => $ansRes['code'],
            'Results msg' => $ansRes['msg'],
            'Results note' => $ansRes['note'],
        ];
    }
}
