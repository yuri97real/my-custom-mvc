<?php

require_once __DIR__."/../config.php";
require_once __DIR__."/../vendor/autoload.php";

$router = new Core\Router("App", APP_URL);

$router->static("/local", "app/Views/pages", ["js", "css"]);
$router->static("/assets", "app/Views/assets", ["js", "css"]);

$router->dispatch("ErrorController::index");