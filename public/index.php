<?php

require_once "../vendor/autoload.php";
require_once "../config.php";

$cors = new App\Core\Cors();

$cors->setMethods([
    "GET", "POST", "PUT", "DELETE"
]);

/*$cors->setDomains([
    "https://www.google.com"
]);*/

$cors->use();

$router = new App\Core\Router;

// html (home)
$router->get("/", "HomeController::index");
$router->get("/home", "HomeController::index");
// html (error)
$router->get("/error/{code}", "ErrorController::index");
// json
$router->get("/about", "HomeAPI::index")->dir("Api");
$router->get("/error/api", "ErrorAPI::error404")->dir("Api");
$router->get("/error/api/{code}", "ErrorAPI::index")->dir("Api");
// databases
$router->get("/databases", "DatabaseAPI::index")->dir("Api");

$router->dispatch();