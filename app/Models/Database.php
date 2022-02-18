<?php

namespace App\Models;

use Core\Model;

class Database extends Model {

    public function getAll()
    {
        return $this->exec("SHOW DATABASES");
    }

}
