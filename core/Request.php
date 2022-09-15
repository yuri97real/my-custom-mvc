<?php

namespace Core;

interface iRequest {
    public function authorization();
}

class Request implements iRequest {

    public $body, $query, $params;

    public function __construct(array $params)
    {
        $this->query = $_GET;
        $this->params = $params;

        if( empty($_POST) ) return $this->setBody();

        $this->body = $_POST;
    }

    public function authorization(): string
    {
        $headers = getallheaders();
        return $headers["Authorization"] ?? ($headers["authorization"] ?? "");
    }

    private function setBody()
    {
        $content = file_get_contents("php://input");

        if( ($_SERVER["CONTENT_TYPE"] ?? "") !== "application/json" ) return $this->parseString($content);
        
        $this->body = json_decode($content, true);
    }

    private function parseString(string $content)
    {
        parse_str($content, $body);
        $this->body = $body;
    }

}