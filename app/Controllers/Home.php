<?php

namespace App\Controllers;

use Core\iRequest;
use Core\iResponse;

class Home {

    public function index(iRequest $request, iResponse $response)
    {
        $response->view("home/index");
    }

}
