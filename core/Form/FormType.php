<?php

namespace Core\Form;

class FormType
{
    private array $params;

    public function getParams(): array
    {
        return $this->params;
    }



    public function add(FormParam $param)
    {
        $this->params[] = $param;
    }


    public function getValue(string $paramName): string | bool
    {

        $result = null;

        foreach ($this->params as $param) {
            if ($param->getInputName() == $paramName) {

                $result =  $param->getInputValue();
            }

        }
        return $result;
    }


    public function isSubmitted(): bool
    {

        foreach ($this->params as $param) {

            $result = $param->isSubmitted();
            if (!$result) {



                return false;
            }

            $param->setInputValue($result);

        }




        return true;
    }

}