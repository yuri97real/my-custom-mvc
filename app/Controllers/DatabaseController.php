<?php

use App\Core\Controller;

class DatabaseController extends Controller {

    public function index()
    {
        $model = $this->model("show");
        
        $databases = $model->getDatabases();

        $this->json($databases);
    }

}