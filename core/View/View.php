<?php

namespace Core\View;

class View
{
    public static function render(string $templateName, array $data = null)
    {

        if($data){
            extract($data);
        }



        if(!isset($pageTitle)){
            $pageTitle = "Mon application";
        }
        ob_start();

        require_once "../templates/$templateName.html.php";

        $pageContent =  ob_get_clean();


        ob_start();

        require_once "../templates/base.html.php";

        echo ob_get_clean();



    }
}