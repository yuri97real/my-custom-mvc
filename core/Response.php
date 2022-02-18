<?php

namespace Core;

interface iResponse {

    public function view(string $view, array $data = []);
    public function json(array $body);
    public function status(int $code);

}

class Response implements iResponse {

    private $response = [
        "code"=> 200,
    ];

    public function view(string $view, array $data = []): void
    {
        http_response_code($this->response["code"]);
        require_once ROOT."/app/Views/{$view}.php"; die;
    }

    public function json(array $body = []): void
    {
        header("Content-Type: application/json; charset=utf-8");
        
        http_response_code($this->response["code"]);

        $this->response["body"] = $body;

        die( json_encode($this->response) );
    }

    public function status(int $code): iResponse
    {
        $this->response["code"] = $code;
        return $this;
    }

}