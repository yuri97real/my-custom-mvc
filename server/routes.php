<?php

$router = new Core\Router("Server", SERVER_URL);

$router->static("/uploads", "server/Storage/uploads");

$router->dispatch("ErrorController::index");