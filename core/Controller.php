<?php

namespace app\core;


class Controller
{
    protected View $view;
    protected array $config;

    public function __construct()
    {
        $this->view = new View();
        $this->config = Application::$config;
    }

    protected function prepareData($apiRes, $sentAns, $ansRes)
    {
        return [
            'Token' => $apiRes['token'],
            'Task msg.' => $apiRes['task']['msg'],
            'Answer' => is_array($sentAns) ? json_encode($sentAns) : $sentAns,
            'Results code' => $ansRes['code'],
            'Results msg' => $ansRes['msg'],
            'Results note' => $ansRes['note'] ?? 'brak danych',
        ];
    }


    public function isNestedArray($array){
        foreach($array as $value) {
            if(is_array($value)){
                return true;
            }
        }
        return false;
    }
}
