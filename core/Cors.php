<?php

namespace Core;

class Cors {

    private $allowed_domains = [];
    private $allowed_methods = ["GET", "POST"];

    public function use()
    {
        $methods = implode(", ", $this->allowed_methods);

        header("Access-Control-Allow-Methods: {$methods}");
        
        $http_origin = $this->getHttpOrigin();
        $allowed = in_array($http_origin, $this->allowed_domains);

        if(!$allowed) return;
        
        header("Access-Control-Allow-Origin: {$http_origin}");
    }

    public function setMethods(array $allowed_methods)
    {
        $this->allowed_methods = $allowed_methods;
    }

    public function setDomains(array $allowed_domains)
    {
        $this->allowed_domains = $allowed_domains;
    }

    private function getHttpOrigin()
    {
        $http_origin = $_SERVER['HTTP_ORIGIN'] ?? (
            $_SERVER['HTTP_REFERER'] ?? $_SERVER['REMOTE_ADDR']
        );

        $relative_origin = str_replace("www.", "", $http_origin);

        array_push($this->allowed_domains, $relative_origin);

        return $http_origin;
    }

}