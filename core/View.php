<?php

namespace app\core;

class View
{

    public function __construct()
    {
    }

    public function main($param = null)
    {
        $page = dirname(__DIR__) . '/view/page/main.php';

        if (file_exists($page)) {
            include $page;
        }
    }
}
