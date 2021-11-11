<?php

use App\Core\iRequest;
use App\Core\iResponse;

class HomeAPI {

    public function index(iRequest $request, iResponse $response)
    {
        $response->json([
            "title"=> "MVC API",
            "message"=> "Welcome " . $_SERVER["REMOTE_ADDR"],
            "developer"=> "Yuri Seabra Maciel"
        ]);
    }

}