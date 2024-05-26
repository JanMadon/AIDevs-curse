<?php

namespace app\core;

class Router
{
    public View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === "GET";
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    public function get($path, $callback): void
    {
        if($path == '/'){
            $this->view->main();
        }

        if($path == '/helloapi'){
            
        }
    }

    // public function post($path, $callback): void
    // {
    //     $this->routes['post'][$path] = $callback;
    // }

}