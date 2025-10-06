<?php

namespace Core\Loader;

class FileScanning
{

    private array $components;

    public function __construct()
    {
        $this->addComponent("controllers",
        [
            "namespace"=>"App\\Controller",
            "dir"=>__DIR__."/../../src/Controller",
        ]);

    }
    public function addComponent(string $name, array $data)
    {
        $this->components[$name] = $data;
    }

    public function loadClassesFromComponents(array $component)
    {
        $dir = $component["dir"];
        $namespace = $component["namespace"];
        $classes = [];

        foreach(scandir($dir) as $file)
            {

                if(str_ends_with($file, ".php")){
                    $className = $namespace . "\\" . pathinfo($file, PATHINFO_FILENAME);
                    if(class_exists($className)){
                        $classes[] = new $className;
                    }
                }
            }


        return $classes;

    }

    public function getControllers()
    {

        return $this->loadClassesFromComponents($this->components["controllers"]);
    }


}