<?php

use App\Core\Controller;

class HomeController extends Controller {

    public function default()
    {
        $this->view("home/index", [
            "title"=>"Home Page"
        ]);
    }

}