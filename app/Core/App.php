<?php

namespace App\Core;

class App {

    private $route, $mainPage = "home";

    public function __construct()
    {
        $this->route = $this->parseURL();

        $controller = $this->getController();
        $method = $this->getMethod($controller);
        $params = $this->getParams();

        call_user_func([$controller, $method], $params);
    }

    public function parseURL()
    {
        $url = $_SERVER["REQUEST_URI"];

        $url = explode("/", $url);
        $url = array_filter($url);
        $url = filter_var_array($url, FILTER_SANITIZE_STRIPPED);

        return array_values($url);
    }

    public function getController()
    {
        $class = $this->route[0] ?? $this->mainPage; //tentar receber um controlador pela url, senão, padrão definido na $mainPage
        $class = ucfirst($class) . "Controller";

        $path = ROOT . "/app/Controllers";

        if(!file_exists("{$path}/{$class}.php")) $class = "ErrorController"; //se o controlador for setado na url, mas não existir, renomear classe para "ErrorController"

        require_once "{$path}/{$class}.php"; //3 possíveis respostas... 1. Padrão $mainPage, 2. "error" ou 3. O que está na url

        return new $class;
    }

    public function getMethod($class)
    {
        $method = $this->route[1] ?? "index"; //tentar receber um método pela url, senão, padrão "index"
        $isInvalidMethod = !method_exists($class, $method) || !is_callable([$class, $method]);

        if($isInvalidMethod) { //caso o método seja setado, mas não existir, padrão "index"
            header("Location: /error"); die();
        }

        return $method;
    }

    public function getParams()
    {
        unset($this->route[0], $this->route[1]); //remover controlador e método da url, o que sobrar são parâmetros
        return $this->route ? array_values($this->route) : []; //tentar receber parametros da url
    }

}