<?php

namespace App\Controllers;

use Core\iRequest;
use Core\iResponse;

class ErrorController {

    public static function index(iRequest $request, iResponse $response)
    {
        return $response->status(404)->view("error/index", [
            "title"=> "Page not found!"
        ], "default");
    }

}