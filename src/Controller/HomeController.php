<?php

namespace App\Controller;

use Core\Controller\Controller;

class HomeController extends Controller
{



    public function index()
    {
       return $this->render('home/documentation', []);
    }
}