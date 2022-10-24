<?php

namespace Core;

class Router {

    private $namespace, $prefix = "";

    private $baseURL, $parsedURI;
    private $currentMethod, $currentRoute, $currentName;

    private $routeList = [], $routeNames = [];

    public function __construct(string $namespace, string $baseURL)
    {
        $this->namespace = $namespace;
        $this->baseURL = $baseURL;
        $this->parsedURI = $this->parseURI();

        if( !IN_PRODUCTION ) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    public function get(string $route, $handler)
    {
        return $this->pushRoute("GET", $route, $handler);
    }

    public function post(string $route, $handler)
    {
        return $this->pushRoute("POST", $route, $handler);
    }

    public function put(string $route, $handler)
    {
        return $this->pushRoute("PUT", $route, $handler);
    }

    public function delete(string $route, $handler)
    {
        return $this->pushRoute("DELETE", $route, $handler);
    }

    public function static(string $route, string $dirname, array $allowed = [])
    {
        $dirname = ROOT."/{$dirname}";
        $this->routeList["GET"]["{$route}/{folder}/{basename}"]["handler"] = "FileController::show";
        $this->routeList["GET"]["{$route}/{folder}/{basename}"]["params"] = compact("dirname", "allowed");
    }

    public function name(string $index)
    {
        $index = $this->prefix.$index;

        $this->currentName = $index;
        $this->routeNames[$index] = $this->currentRoute;

        return $this;
    }

    public function middlewares(array $classes)
    {
        $route = $this->currentRoute;
        $method = $this->currentMethod;

        $this->routeList[$method][$route]["middlewares"] = $classes;
        
        return $this;
    }

    public function current()
    {
        return [
            "method"=> $this->currentMethod,
            "route"=> $this->currentRoute,
            "name"=> $this->currentName,
        ];
    }

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function group(array $routes, array $mainMiddlewares = [])
    {
        foreach($routes as $currentData) :

            $method = $currentData["method"];
            $route = $currentData["route"];

            $oldMiddlewares = $this->routeList[$method][$route]["middlewares"] ?? [];

            $this->routeList[$method][$route]["middlewares"] = [
                ...$mainMiddlewares,
                ...$oldMiddlewares,
            ];

        endforeach;

        $this->prefix = "";
    }

    public function dispatch($defaultHandler)
    {
        if( !IN_PRODUCTION ) {

            $routes = $this->routeNames;
            $routes["baseURL"] = $this->baseURL;

            $json = json_encode($routes);

            file_put_contents(ROOT."/routes/{$this->namespace}.json", $json);

        }

        print_r( $this->finish($defaultHandler) );
    }

    private function parseURI()
    {
        $requestURIWithoutQueryString = isset($_SERVER["QUERY_STRING"]) ? str_replace(
            "?".$_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]
        ) : $_SERVER["REQUEST_URI"];

        $arrayBaseURL = explode("/", $this->baseURL);
        $arrayBaseURLWithoutOrigin = array_slice($arrayBaseURL, 3);

        $restOfTheRoute = str_replace($arrayBaseURLWithoutOrigin, "", $requestURIWithoutQueryString);
        $restOfTheRoute = trim($restOfTheRoute, "/");

        return strlen($restOfTheRoute) < 2 ? "/" : "/{$restOfTheRoute}";
    }

    private function pushRoute(string $method, string $route, $handler)
    {
        $route = $this->prefix == "" ? $route : rtrim("/{$this->prefix}$route", "/");

        $this->currentRoute = $route;
        $this->currentMethod = $method;

        $this->routeList[$method][$route]["handler"] = $handler;

        return $this;
    }

    private function getRouteData(array $routeList, $defaultHandler)
    {
        if( isset($routeList[$this->parsedURI]) ) return $routeList[$this->parsedURI];

        $parsedRoutePieces = explode("/", $this->parsedURI);
        $parsedRouteLength = count($parsedRoutePieces);

        foreach($routeList as $currentURI => $currentData) :

            $currentRoutePieces = explode("/", $currentURI);
            if( $parsedRouteLength != count($currentRoutePieces) ) continue;

            $paramKeys = $this->extractParamKeys($currentURI);
            if( empty($paramKeys) ) continue;

            $paramValues = array_diff_assoc($parsedRoutePieces, $currentRoutePieces);
            if( count($paramKeys) != count($paramValues) ) continue;

            $filledParams = array_combine($paramKeys, $paramValues);
            $currentData["params"] = array_merge($filledParams, $currentData["params"] ?? []);

            return $currentData;

        endforeach;

        return [ "handler"=> $defaultHandler ];
    }

    private function extractParamKeys(string $route): array
    {
        $params = explode("{", $route);

        if(count($params) === 1) return [];

        unset($params[0]);

        return array_map(function($param) {
            $index = strpos($param, "}");
            return substr($param, 0, $index);
        }, $params);
    }

    private function finish($defaultHandler)
    {
        define("BASE_URL", $this->baseURL);
        define("ROUTE_NAMES", $this->routeNames);

        $method = $_POST["__method__"] ?? $_SERVER["REQUEST_METHOD"];

        unset($_POST["__method__"]);

        $routeList = $this->routeList[$method] ?? [];
        $routeData = $this->getRouteData($routeList, $defaultHandler);

        extract($routeData);

        $request = new Request($params ?? []);
        $response = new Response;

        if( isset($middlewares) )
            foreach($middlewares as $middleware) { call_user_func("Middlewares\\{$middleware}", $request, $response); }

        return is_callable($handler) ?
            $handler($request, $response) :
            call_user_func("{$this->namespace}\\Controllers\\{$handler}", $request, $response);
    }

}