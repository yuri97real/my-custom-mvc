<?php

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

$http_origin = $_SERVER['HTTP_ORIGIN'] ?? "";

$allowed_domains = [
    "http://localhost:3333"
];

if(in_array($http_origin, $allowed_domains)) {
    header("Access-Control-Allow-Origin: {$http_origin}");
}
