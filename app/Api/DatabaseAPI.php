<?php

use App\Core\iRequest;
use App\Core\iResponse;

use App\Models\ShowModel;

class DatabaseAPI {

    public function index(iRequest $request, iResponse $response)
    {
        $databases = (new ShowModel)->getDatabases();
        $response->json($databases);
    }

}