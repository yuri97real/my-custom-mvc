<?php

$router = new Core\Router("Server", SERVER_URL);

$router->dispatch("ErrorController::index");