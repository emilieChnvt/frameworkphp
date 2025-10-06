<?php

namespace App\Form;

use Core\Form\FormParam;
use Core\Form\FormType;

class PostType extends FormType
{

    public function __construct()
    {
        $this->build();
    }

    public function build()
    {
        $this->add(new FormParam("title", "text"));
        $this->add(new FormParam("content", "text"));
    }

}