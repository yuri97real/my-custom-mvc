<?php

namespace App\API;

use Core\iRequest;
use Core\iResponse;

use Core\Limiter;

use App\Models\Database;

class JSON {

    public function index(iRequest $request, iResponse $response)
    {
        $db_model = new Database;
        $databases = $db_model->getAll();

        $response->json($databases);
    }

    public function limiter(iRequest $request, iResponse $response)
    {
        $limiter = new Limiter("example");
        
        $limiter->limitRequests(2, 5, function($time_left) use ($response) {

            $response->json([
                "message"=> "Access limit exceeded. Wait {$time_left} seconds.",
            ]);

        });

        $response->json([
            "message"=> "Hello World!",
        ]);
    }

}
