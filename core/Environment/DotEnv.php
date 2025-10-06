<?php

namespace Core\Environment;

class DotEnv
{
    protected $path;

    public function __construct()
    {
        $this->path = __DIR__."/../../.env";

    }


    private function load() : void
    {
        if(!is_readable($this->path))
        {
            throw new \Exception("Unable to read env file");
        }
        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line)
        {
            if(strpos(trim($line), "#") === 0)
            {
                continue;
            }
            list($variableName, $variableValue) = explode("=", $line, 2);
            $variableName = trim($variableName);
            $variableValue = trim($variableValue);


            if(!array_key_exists($variableName, $_SERVER) && !array_key_exists($variableName, $_ENV))
            {
                putenv(sprintf('%s=%s', $variableName, $variableValue));
                $_ENV[$variableName] = $variableValue;
                $_SERVER[$variableName] = $variableValue;
            }

        }


    }

    public function getVariable(string $variableName):string
    {
        $this->load();
        return getenv($variableName);
    }


}