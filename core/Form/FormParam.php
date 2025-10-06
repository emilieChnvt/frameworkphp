<?php

namespace Core\Form;

use Core\Http\Request;

class FormParam
{

    private string $inputName;
    private string $inputDataType;

    private string $inputValue;



    public function __construct(string $inputName, string $inputDataType)
    {
        $this->inputName = $inputName;
        $this->inputDataType = $inputDataType;
    }

    public function isSubmitted(): bool | string | int
    {
        $request = new Request();

        return $request->get([$this->inputName => $this->inputDataType], "post");

    }



    public function getInputName(): string
    {
        return $this->inputName;
    }

    public function setInputName(string $inputName): void
    {
        $this->inputName = $inputName;
    }

    public function getInputDataType(): string
    {
        return $this->inputDataType;
    }

    public function setInputDataType(string $inputDataType): void
    {
        $this->inputDataType = $inputDataType;
    }

    public function getInputValue(): string
    {
        return $this->inputValue;
    }

    public function setInputValue(string $inputValue): void
    {
        $this->inputValue = $inputValue;
    }


}