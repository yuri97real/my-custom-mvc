<?php

require_once __DIR__."/../config.php";
require_once __DIR__."/../vendor/autoload.php";

$http_origin = $_SERVER['HTTP_ORIGIN'] ?? "";
$relative_origin = str_replace("www.", "", $http_origin);

$cors = new Core\Cors();

$cors->setMethods([
    "GET", "POST", "PUT", "DELETE"
]);

$cors->setDomains([
    $relative_origin,
]);

$cors->use($http_origin);

require_once __DIR__."/routes.php";