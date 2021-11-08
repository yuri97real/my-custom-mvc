<?php

use App\Core\Controller;

use App\Core\iRequest;
use App\Core\iResponse;

class DatabaseAPI extends Controller {

    public function index(iRequest $request, iResponse $response)
    {
        $model = $this->model("show");
        $databases = $model->getDatabases();

        $response->json($databases);
    }

}