<?php

$router = new Core\Router(true);

$router->namespace("Controllers");
// your routes here

$router->dispatch();