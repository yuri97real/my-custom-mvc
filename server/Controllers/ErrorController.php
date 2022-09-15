<?php

namespace Server\Controllers;

use Core\iRequest;
use Core\iResponse;

class ErrorController {

    public static function index(iRequest $request, iResponse $response)
    {
        return $response->status(404)->json([
            "title"=> "Page not found!",
        ]);
    }

}