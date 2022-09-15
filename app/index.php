<?php

require_once __DIR__."/../config.php";
require_once __DIR__."/../vendor/autoload.php";

$router = new Core\Router("App", APP_URL);

$router->dispatch("ErrorController::index");