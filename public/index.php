<?php

require_once "../vendor/autoload.php";
require_once "../config/config.php";

$cors = new Core\Cors();

$cors->setMethods([
    "GET", "POST", "PUT", "DELETE"
]);

/*$cors->setDomains([
    "https://www.google.com"
]);*/

$cors->use();

require_once "./routes.php";