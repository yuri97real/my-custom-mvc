<?php

$router = new Core\Router(true);

$router->namespace("Controllers");

$router->get("/", "Home::index");
$router->get("/contatos", function($req, $res) {

    $res->view("contacts/index");

});

$router->namespace("API");

$router->get("/databases", "JSON::index");

$router->dispatch();