<?php

use App\Core\Model;

class ShowModel extends Model {

    public function getDatabases()
    {
        if(DB_CONFIG["driver"] != "mysql") return [
            "Message"=>"Break: This Method Only Works With MySQL Queries"
        ];

        $pdo = $this->getPDO();

        $results = $pdo->prepare("SHOW DATABASES");
        $results->execute();

        return $results->fetchAll();

    }

}