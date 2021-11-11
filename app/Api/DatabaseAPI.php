<?php

use App\Core\iRequest;
use App\Core\iResponse;

use App\Core\Model;

class DatabaseAPI {

    public function index(iRequest $request, iResponse $response)
    {
        $pdo = (new Model)->getPDO();

        $stmt = $pdo->prepare("SHOW DATABASES");
        $stmt->execute();

        $databases = $stmt->fetchAll();

        $response->json($databases);
    }

}