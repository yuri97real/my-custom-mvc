<?php

namespace App\Core;

abstract class Controller {

    public function model($model)
    {
        $classname = ucfirst($model) . "Model";

        require_once ROOT . "/app/Models/{$classname}.php";

        return new $classname;
    }

    public function redirect($route = "/home")
    {
        header("Location: {$route}"); die;
    }

    public function console($data)
    {
        echo "<pre>"; print_r($data); die;
    }

}