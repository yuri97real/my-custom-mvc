<?php

namespace Core;

class Router {

    private $url, $namespace;
    
    private $route_list = [
        "GET"=> [],
        "POST"=> [],
        "PUT"=> [],
        "DELETE"=> []
    ];

    public function __construct(bool $logs = false)
    {
        $this->url = $this->parseURL();

        if($logs) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    private function parseURL()
    {
        $query = $_SERVER["QUERY_STRING"] ?? "";
        $request_uri = $this->parseURI();

        $url = str_replace("?{$query}", "", $request_uri);

        return strlen($url) < 2 ? "/" : rtrim($url, "/");
    }

    private function parseURI()
    {
        $port = $_SERVER["SERVER_PORT"] == 80 ? "" : ":".$_SERVER["SERVER_PORT"];
        
        $base_url = str_replace(["http://", "https://"], "", BASE_URL);
        $full_url = $_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];

        return str_replace($base_url, "", $full_url);
    }

    public function dispatch()
    {
        $request_method = $_SERVER["REQUEST_METHOD"];
        $routes = $this->route_list[$request_method] ?? [];

        if(empty($routes)) return;

        extract( $this->getRouteData($routes) );

        $request = new Request($params ?? []);
        $response = new Response;

        if( is_callable($handler) ) {
            $handler($request, $response); die;
        }

        $schema = explode("::", $handler);

        $controller = $namespace.$schema[0];
        $method = $schema[1];

        call_user_func([new $controller, $method], $request, $response);
    }

    private function getRouteData(array $routes)
    {
        if( isset($routes[$this->url]) ) {
            return $routes[$this->url];
        }

        $piecesURL = explode("/", $this->url);
        $lengthURL = count($piecesURL);

        foreach($routes as $route => $data) {

            $piecesRoute = explode("/", $route);
            if($lengthURL != count($piecesRoute)) continue;

            $vars = $this->extractVars($route);
            if(empty($vars)) continue;

            $diff = array_diff_assoc($piecesURL, $piecesRoute);

            if(count($diff) != count($vars)) continue;

            return [
                "handler"=> $data["handler"],
                "namespace"=> $data["namespace"],
                "params"=> array_fill_keys($vars, current($diff)),
            ];

        }

        return [
            "handler"=> "Error::index",
            "namespace"=> "Core\\",
        ];
    }

    private function extractVars(string $route): array
    {
        $vars = explode("{", $route);

        if(count($vars) === 1) return [];

        unset($vars[0]);

        return array_map(function($var) {
            $index = strpos($var, "}");
            return substr($var, 0, $index);
        }, $vars);
    }

    private function verb(string $route, mixed $handler, string $verb)
    {
        $this->route_list[$verb][$route] = [
            "handler"=> $handler,
            "namespace"=> $this->namespace,
        ];
    }

    public function get(string $route, mixed $handler)
    {
        $this->verb($route, $handler, "GET");
    }

    public function post(string $route, mixed $handler)
    {
        $this->verb($route, $handler, "POST");
    }

    public function put(string $route, mixed $handler)
    {
        $this->verb($route, $handler, "PUT");
    }

    public function delete(string $route, mixed $handler)
    {
        $this->verb($route, $handler, "DELETE");
    }

    public function namespace(string $namespace)
    {
        $this->namespace = "App\\{$namespace}\\";
    }

}