<?php

namespace app\core;


class Controller
{
    public View $view;

    public function __construct()
    {
        $this->view = new View();
    }
}
