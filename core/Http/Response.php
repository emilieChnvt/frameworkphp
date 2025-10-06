<?php

namespace Core\Http;

use Core\Routing\Router;
use Core\View\View;

class Response
{
public function redirect( array $urlParams = null)
    {
        $urlEnd = "";

        if($urlParams)
        {
            $urlEnd = "?";
            foreach($urlParams as $paramName => $paramValue)
            {
                $urlEnd .= $paramName . "=" . $paramValue . "&";
            }
            $urlEnd = substr($urlEnd, 0, -1);
        }


        header("Location: index.php$urlEnd");
        //exit();

        return $this;

    }

    public function redirectToRoute(string $routename, array $params = null)
    {
        $router = new Router();

        try{
            $uri = $router->getUriFromRouteName($routename);

        } catch (\Exception $e) {
            throw $e;
        }

        if($params)
        {
            $uriParams = "?";
            foreach($params as $paramName => $paramValue)
            {
                $uriParams .= $paramName . "=" . $paramValue . "&";

            }
            $uriParams = substr($uriParams, 0, -1);

            $uri.=$uriParams;
        }



        header("Location: $uri");


        return $this;
    }

public function render(string $templateName, array $data)
{
    View::render($templateName, $data);

    return $this;

}

}