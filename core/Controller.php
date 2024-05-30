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
}
