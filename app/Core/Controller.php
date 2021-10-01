<?php

namespace App\Core;

abstract class Controller {

    public function model($model)
    {
        $obj_name = ucfirst($model) . "Model";
        $namespace = "\\App\\Models\\{$obj_name}";

        require_once ROOT . "/app/Models/{$obj_name}.php";

        return new $namespace;
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
        header("Location: {$route}"); die();
    }

}