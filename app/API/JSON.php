<?php

namespace App\API;

use Core\iRequest;
use Core\iResponse;

use App\Models\Database;

class JSON {

    public function index(iRequest $request, iResponse $response)
    {
        $db_model = new Database;
        $databases = $db_model->getAll();

        $response->json($databases);
    }

}
