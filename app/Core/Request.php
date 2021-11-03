<?php

namespace App\Core;

abstract class Request {

    private $data = [
        "GET"=> [],
        "POST"=> [],
        "PUT"=> [],
        "DELETE"=> [],
        "PATCH"=> []
    ];

    private function setRequestData()
    {
        $request_method = $_SERVER["REQUEST_METHOD"];
        $valid_method = isset($this->data[$request_method]);

        if(!$valid_method) $this->response(405);
        
        $this->data["GET"] = $_GET;
        $_GET = [];
        
        if($request_method == "GET") return;

        $content = file_get_contents("php://input");
        $decoded = $this->decodeBody($content);

        $this->data[$request_method] = $decoded;

        $_POST = [];
    }

    private function decodeBody($content)
    {
        if($content == "" && !empty($_POST)) return $_POST;

        $first = substr($content, 0, 1);
        $last = substr($content, -1, 1);
        
        if($first == "{" && $last == "}") return (array) json_decode($content);

        if(stripos($content, "boundary") === false) {
            parse_str($content, $data);
            return $data;
        }

        $keyword = "\r\nContent-Disposition: form-data;";
        $pieces = explode($keyword, $content);

        $boundary = $pieces[0];

        $pieces = str_replace([
            $boundary, "\n", "\r", "--", 'name="'
        ], "", $pieces);

        $pieces = str_replace('"', "=", $pieces);
        $content = implode("&", $pieces);

        parse_str($content, $data);
        
        return $data;
    }

    public function body($method)
    {
        require_once ROOT . "/cors.php";
        
        $this->setRequestData();

        $method = mb_strtoupper($method);

        return $this->data[$method] ?? $this->response(405);
    }

    public function authorization()
    {
        $headers = apache_request_headers();
        return $headers["Authorization"] ?? "";
    }

    public function json(array $data = [])
    {
        echo json_encode($data); die;
    }

    public function response(int $code = 200, array $data = [])
    {
        require_once ROOT."/status.php";

        $response = $status[$code] ?? $status[409];

        http_response_code($response["status"]);

        if(!empty($data)) $response["body"] = $data;

        $this->json($response);
    }

}