<?php

namespace App\Models;

use App\Core\Model;

class ShowModel extends Model {

    public function getDatabases()
    {
        $databases = $this->exec("SHOW DATABASES");
        return $databases->fetchAll();
    }

}