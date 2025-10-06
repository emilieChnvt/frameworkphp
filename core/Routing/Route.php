<?php

namespace Core\Routing;

class Route
{
    private string $uri;
    private string $method;
    private string $controller;

    private array $verbs;
    private string $routeName;

    public function matches(string $uri): bool
    {
        if ($this->uri === $uri) {
            return true;
        }
        return false;
    }

//    public function __construct(string $uri, string $method, string $controller, string $routeName)
//    {
//        $this->uri = $uri;
//        $this->method = $method;
//        $this->controller = $controller;
//        $this->routeName = $routeName;
//
//    }


    public function getUri(): string
    {
        return $this->uri;
    }



    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getVerbs(): array
    {
        return $this->verbs;
    }

    public function setVerbs(array $verbs): void
    {
        $this->verbs = $verbs;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }


}