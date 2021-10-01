<?php

use App\Core\Controller;

class ErrorController extends Controller {

    public function index()
    {
        $this->view("error/index", [
            "title"=>"Error 404", "favicon"=>"error.ico"
        ]);
    }

}