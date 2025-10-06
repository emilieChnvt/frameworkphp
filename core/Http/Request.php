<?php

namespace Core\Http;

class Request
{

    private string $uri;

    private array $globals;

    private array $globalGet;

    private array $globalPost;
    public function __construct()
    {
        $this->globals = $this->resolveGlobals();
        $this->resolveGlobalGet();
        $this->resolveGlobalPost();


        $uri =  parse_url($this->globals['REQUEST_URI'], PHP_URL_PATH);
        $this->uri = rtrim($uri, '/') ? : '/';

    }
    public function getGlobals()
    {
        return $this->globals;

    }

    public function getUri()
    {
        return $this->uri;
    }


    public function resolveGlobals()
    {
        return $_SERVER;
    }

    public function resolveGlobalGet()
    {
        $this->globalGet = $_GET;
    }

    public function resolveGlobalPost()
    {
            $this->globalPost = $_POST;
    }

    // $id = $this->getRequest()->get(["id"=>"number")

    public function get(array $parameters, $method = null)
    {

        $results = [];

        if(!$method ) {$globalMethod = $this->globalGet;}
        elseif($method === "post"){$globalMethod = $this->globalPost;}


        foreach ($parameters as $paramName => $dataType) {

            if(!isset($globalMethod[$paramName]) || empty($globalMethod[$paramName])) {
                return false;
            }

            $param = $globalMethod[$paramName];

            switch($dataType) {
                case 'number':
                    if(!ctype_digit($param)) {
                        return false;
                    }
                    $param = (int)$param;
                    break;

                case 'text' :
                    if(empty($param)) {
                        return false;
                    }
                    break;

            }

            $param = htmlspecialchars($param);

            $results[$paramName] = $param;


        }
        if($results === []) {return false;}
        if(sizeof($results) === 1) {return $results[$paramName];}
        return $results;
    }




}