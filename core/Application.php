<?php

namespace app\core;

class Application
{
    public Router $router;
    public Request $request;
    
    public function __construct(private array $conf)
    {
        dd($conf);
        $this->router = new Router();
        $this->request = new Request();

    }

    public function run()
    {
        // musisz sprawdzić jaki rodzaj requestu
        // jeśli nic to defoltowy

    }
}
