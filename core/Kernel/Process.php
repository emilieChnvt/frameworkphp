<?php

namespace Core\Kernel;

use Core\Routing\Route;

class Process
{

    private string $controller;
    private string $action;


    public function __construct(Route $route)
    {
        $this->controller = $route->getController();
        $this->action = $route->getMethod();
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }


}