<?php

use App\Core\Controller;

class HomeAPI extends Controller {

    public function default()
    {
        $this->response(200, [
            "title"=> "MVC API",
            "message"=> "Welcome " . $_SERVER["REMOTE_ADDR"],
            "developer"=> "Yuri Seabra Maciel"
        ]);
    }

}