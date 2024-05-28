<?php

namespace app\core;

class Request
{

    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === "GET";
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    public function getBody($param = null)
    {
        $body = [];

        if($this->isGet()){
            $body = $_GET;
        }

        if($this->isPost()){
            $body = $_POST;
        }

        return $param ? $body[$param] : $body; 
    }

}