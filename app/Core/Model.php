<?php

namespace App\Core;

use Exception;

abstract class Model {

    private $pdo;

    public function __construct()
    {
        try {

            $this->pdo = new \PDO(
                DB_CONFIG["driver"] . ":host=" .
                DB_CONFIG["host"] . ";charset=utf8mb4",
                DB_CONFIG["username"],
                DB_CONFIG["password"],
                DB_CONFIG["options"]
            );

        } catch(Exception $e) {

            die("Erro No MySQL (Ação: Finalizar): " . $e->getMessage());

        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }

}