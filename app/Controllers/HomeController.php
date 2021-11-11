<?php

use App\Core\iRequest;
use App\Core\iResponse;

class HomeController {

    public function index(iRequest $request, iResponse $response)
    {
        $response->view("home/index", [
            "title"=>"Home Page"
        ]);
    }

}