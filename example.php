<?php

define("ROOT", __DIR__);
define("IN_PRODUCTION", false);

define("APP_URL", "http://localhost/Simple-MVC-Structure");
define("SERVER_URL", "http://localhost/Simple-MVC-Structure/server");

define("DB_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "example",
    "username" => "root",
    "password" => "",
    "options" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ],
]);