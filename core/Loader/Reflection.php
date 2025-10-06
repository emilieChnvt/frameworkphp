<?php

namespace Core\Loader;


use Core\Attributes\Route as RouteAttribute;

class Reflection
{

    public function getRoutesFromControllers():array
    {

        $fs = new FileScanning();

        $controllers = $fs->getControllers();


        $routes = [];

        foreach($controllers as $controller) {

            $reflection = new \ReflectionClass($controller);
            foreach($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(RouteAttribute::class);

            foreach($attributes as $attribute) {
                $routeAttribute = $attribute->newInstance();
                $routes[] = [
                    "controller"=>$controller,
                    "method"=>$method->getName(),
                    "attribute"=>$routeAttribute
                ];

               }
            }

        }


        return $routes;
    }


}