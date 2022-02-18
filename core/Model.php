<?php

namespace Core;

use Exception;

abstract class Model {

    private $pdo;

    public function __construct($free = false)
    {
        extract(DB_CONFIG);

        $dbname = empty($dbname) || $free ? "" : "dbname={$dbname};";
        $dsn = "{$driver}:host={$host};{$dbname}charset=utf8mb4";
        
        try {

            $this->pdo = new \PDO(
                $dsn,
                $username,
                $password,
                $options
            );

        } catch(Exception $e) {

            die( $e->getMessage() );

        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function exec(string $query, array $values = [], $list = true)
    {
        try {

            $result = $this->pdo->prepare($query);

            empty($values) ?
                $result->execute() :
                $result->execute($values);

            return [
                "data"=> !$list ?
                    $result->fetch() :
                    $result->fetchAll(),
                "affected"=> $result->rowCount(),
            ];

        } catch(Exception $e) {

            return [
                "error"=> $e,
                "affected"=> 0,
            ];

        }
    }

}