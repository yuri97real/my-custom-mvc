<?php

namespace App\Core;

abstract class Controller {

    public function model($model)
    {
        $classname = ucfirst($model) . "Model";

        require_once ROOT . "/app/Models/{$classname}.php";

        return new $classname;
    }

    public function view($view, $data = [])
    {
        require_once ROOT . "/app/Views/template.php";
    }

    public function json(array $data = [])
    {
        echo json_encode($data); die;
    }

    public function redirect($route = "/home")
    {
        header("Location: {$route}"); die;
    }

}