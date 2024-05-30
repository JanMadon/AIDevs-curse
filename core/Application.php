<?php

namespace app\core;

class Application
{

    public Router $router;
    public Request $request;
    
    public static $config;
    
    public function __construct(array $config)
    {
        self::$config = $config;
        $this->router = new Router($config);
        $this->request = new Request();
    }

    public function run()
    {
        // musisz sprawdzić jaki rodzaj requestu
        // jeśli nic to defoltowy

    }
}
