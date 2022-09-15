<?php

namespace Database\Models;

use Core\Model;

class User extends Model {

    public function getAll()
    {
        return $this->execute("SELECT * FROM users");
    }

    public function getByID(int $id)
    {
        $query = "SELECT * FROM users WHERE `id` = ?";
        $values = [ $id ];
        $fetch_all = false;

        return $this->execute($query, $values, $fetch_all);
    }

}