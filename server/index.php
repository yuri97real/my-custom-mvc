<?php

require_once __DIR__."/../config.php";
require_once __DIR__."/../vendor/autoload.php";

$cors = new Core\Cors();

$cors->setMethods([
    "GET", "POST", "PUT", "DELETE"
]);

$cors->setDomains([
    //your domains allowed here!
]);

$cors->use();

require_once __DIR__."/routes.php";