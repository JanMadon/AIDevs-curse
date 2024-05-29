<?php

namespace app\core;

use Exception;

class Router
{

    public function get($path, $callback): void
    {
        $this->validate($callback);

        list($controllerClass, $method) = $callback;
        $controller = new $controllerClass();
        $controller->$method();
    }

    public function post($path, $callback): void
    {
        $this->validate($callback);

        list($controllerClass, $method) = $callback;
        $controller = new $controllerClass();
        $controller->$method();
    }

    private function validate($callback)
    {
        if (is_array($callback) && count($callback) === 2) {
            if (class_exists($callback[0]) && method_exists($callback[0], $callback[1])) {
                return true;
            } else {
                throw new Exception('controller or method does not exist.');
            }
        } else {
            throw new Exception('Invalid callback provided.');
        }
    }
}
