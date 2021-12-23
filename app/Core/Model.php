<?php

namespace App\Core;

use Exception;

abstract class Model {

    private $pdo;

    public function __construct()
    {
        extract(DB_CONFIG);

        $dbname = empty($dbname) ? "" : "dbname={$dbname};";
        $dsn = "{$driver}:host={$host};{$dbname}charset=utf8mb4";
        
        try {

            $this->pdo = new \PDO(
                $dsn,
                $username,
                $password,
                $options
            );

        } catch(Exception $e) {

            die("Erro No MySQL (Ação: Finalizar): " . $e->getMessage());

        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function exec(string $query, array $values = [])
    {
        $result = $this->pdo->prepare($query);

        empty($values) ?
            $result->execute() :
            $result->execute($values);

        return $result;
    }

}