<?php

namespace App;

class Application
{
    private $session;

    public function __construct()
    {
        session_start();
        $this->session = FlashMessages::getInstance();
    }

    public function run()
    {
        $router = new  Router($_SERVER['REQUEST_URI']);

        return $router->run();
    }
}