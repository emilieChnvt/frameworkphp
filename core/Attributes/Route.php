<?php

namespace Core\Attributes;

use Attribute;

#[Attribute]
class Route
{

    private string $routeName;
    private string $uri;

    private array $methods;

    public function __construct(string $uri,string $routeName,  array $methods = null)
    {
        $this->routeName = $routeName;
        $this->uri = $uri;

        if(!$methods){
            $this->methods[] = "GET";
        }else{
        $this->methods = $methods;
         }

    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
    public function getMethods(): array
    {
        return $this->methods;
    }

}