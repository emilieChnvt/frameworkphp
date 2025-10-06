<?php

namespace Core\Controller;

use Core\Http\Request;
use Core\Kernel\Process;
use Core\Routing\Router;
use Core\Session\Session;

class Kernel
{

    public static function run()
    {
        Session::start();

        $router = new Router();

        $process = $router->handleRequest(new Request());


      return self::trigger($process);



    }

    public static function trigger(Process $process)
    {
        $type = $process->getController();
        $action = $process->getAction();

        $controller = new $type();
        return $controller->$action();
    }


}