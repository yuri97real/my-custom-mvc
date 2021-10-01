<?php

use App\Core\Controller;

class HomeController extends Controller {

    public function index()
    {
        $this->view("home/index", [
            "title"=>"Home Page"
        ]);
    }

}