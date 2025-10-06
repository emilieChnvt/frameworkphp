<?php

namespace Core\Routing;

use App\Controller\HomeController;
use Core\Environment\DotEnv;
use Core\Http\Request;
use Core\Kernel\Process;
use Core\Loader\Reflection;

class Router
{

    private array $routes = [];


    public function __construct()
    {
        $this->resolveRoutesFromAttributes();
        if(!$this->resolveRoute('/'))
        {
            $route = new Route();
            $route->setController(HomeController::class);
            $route->setMethod('index');
            $route->setUri('/');
            $route->setVerbs(['GET']);
            $route->setRouteName('landing_default');

            $this->addRoute($route);
        }

    }


    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    public function handleRequest(Request $request) : Process
    {

        $route = $this->resolveRoute($request->getUri());

        if (!$route) {
            throw new \Exception("Route not found DUMMY !! ");
        }

        return new Process($route);

    }

    private function resolveRoute(string $uri)
    {



        foreach ($this->routes as $route) {

            if ($route->matches($uri)) {
                return $route;
            }

        }
        return false;
    }

    private function resolveRoutesFromAttributes():void
    {
        $ref = new Reflection();
        $routeAttributes = $ref->getRoutesFromControllers();


        foreach ($routeAttributes as $routeAttribute) {
            $route = new Route();
            $route->setUri($routeAttribute["attribute"]->getUri());
            $route->setRouteName($routeAttribute["attribute"]->getRouteName());
            $route->setVerbs($routeAttribute["attribute"]->getMethods());
            $route->setMethod($routeAttribute["method"]);
            $route->setController(get_class($routeAttribute["controller"]));



            $this->addRoute($route);

        }

    }


    public function getUriFromRouteName(string $routeName):string
    {
        $uri = null;
        foreach ($this->routes as $route) {
            if ($route->getRouteName() === $routeName) {
                $uri = $route->getUri();
            }
        }
        if(!$uri)
        {
            throw new \Exception("Route not found YOU DUMMY DEVELOPER!! ");
        }

        return $uri;

    }

}