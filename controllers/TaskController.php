<?php

namespace app\controllers;

use app\AIDevs\Task;
use app\core\Controller;

class TaskController extends Controller
{
    public function helloapi()
    {
        // odpalamy zadanie
        // możmy pobrać terść zadania z DB

        // $task = new Task($this->config); 
        // $res = $task->get('helloapi');
        var_dump($this->config);
        exit();

        

        $this->view->main();
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
