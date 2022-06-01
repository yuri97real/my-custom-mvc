<?php

namespace Core;

class Cors {

    private $allowed_domains = [];
    private $allowed_methods = ["GET", "POST"];

    public function use(string $http_origin)
    {
        $methods = implode(", ", $this->allowed_methods);

        header("Access-Control-Allow-Methods: {$methods}");
        
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

}