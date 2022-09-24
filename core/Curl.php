<?php

namespace Core;

class Curl {

    private $data;

    public function get(string $route, array $params = [])
    {
        return $this->request("GET", $route, $params);
    }

    public function post(string $route, array $params = [])
    {
        return $this->request("POST", $route, $params);
    }

    public function put(string $route, array $params = [])
    {
        return $this->request("PUT", $route, $params);
    }

    public function delete(string $route, array $params = [])
    {
        return $this->request("DELETE", $route, $params);
    }

    public function json(bool $convertToArray = false)
    {
        return json_decode($this->data, $convertToArray);
    }

    public function getData()
    {
        return $this->data;
    }

    private function request(string $method, string $route, array $params)
    {
        $curl = curl_init();

        $body = $params["body"] ?? [];
        $headers = $params["headers"] ?? [];

        curl_setopt_array($curl, [
            CURLOPT_URL=> $route,
            CURLOPT_CUSTOMREQUEST=> $method,
            CURLOPT_RETURNTRANSFER=> true,
            CURLOPT_HTTPHEADER=> $headers,
            CURLOPT_POSTFIELDS=> http_build_query($body),
        ]);

        $this->data = curl_exec($curl);

        curl_close($curl);

        return $this;
    }

}