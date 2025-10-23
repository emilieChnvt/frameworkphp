<?php

namespace App\Form;

use Core\Form\FormParam;
use Core\Form\FormType;

class RegistrationFormType extends FormType
{
    public function __construct()
    {
        $this->build();
    }
    public function build()
    {
        $this->add(new FormParam("email", "text"));
        $this->add(new FormParam("password", "text"));
    }



}