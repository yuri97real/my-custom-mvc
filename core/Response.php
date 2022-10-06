<?php

namespace Core;

interface iResponse {

    public function status(int $code);
    public function view(string $file, array $data = [], string $template = "");
    public function json(array $data);
    public function file(string $filename);
    public function download(string $saveAs, string $filename);
    public function redirect(string $index, array $queryParams = []);

}

class Response implements iResponse {

    public function status(int $code)
    {
        http_response_code($code);
        return $this;
    }

    public function view(string $file, array $data = [], string $template = "")
    {
        extract($data);

        $version = IN_PRODUCTION ? "" : "?v=".rand();

        $static = function (string $filename) use ($version) { return APP_URL."/{$filename}{$version}"; };
        $storage = function (string $filename) use ($version) { return SERVER_URL."/{$filename}{$version}"; };
        
        $constants = function () {
            $dir = ROOT."/routes";

            $app = file_exists("{$dir}/App.json") ? file_get_contents("{$dir}/App.json") : "{}";
            $server = file_exists("{$dir}/Server.json") ? file_get_contents("{$dir}/Server.json") : "{}";

            return "<script>\nconst routes = { app: $app, server: $server };\nconst parsedURI = location.href.replace(routes.app.baseURL, '')\n</script>\n";
        };

        $route = function (string $name, array $params = []) {
            $uri = (ROUTE_NAMES[$name] ?? "/undefined");

            foreach($params as $key => $value) :
                $uri = str_replace("{".$key."}", $value, $uri);
            endforeach;

            return APP_URL.$uri;
        };
        
        $component = function (string $name, array $data = []) use ($route, $static, $storage) {
            extract($data);
            require(ROOT."/app/Views/components/{$name}.php");
        };

        $template === "" ?
            require_once(ROOT."/app/Views/pages/{$file}.php") :
            require_once(ROOT."/app/Views/templates/{$template}.php");
    }

    public function json(array $data)
    {
        header("Content-Type: application/json; charset=utf-8");
        return json_encode($data);
    }

    public function file(string $filename)
    {
        $mime = mime_content_type($filename);
        $mime = $mime == "text/plain" ? "" : $mime;

        header("Content-Type: {$mime}");
        readfile($filename);
    }

    public function download(string $saveAs, string $filename)
    {
        $mime = mime_content_type($filename);
        $mime = $mime == "text/plain" ? "" : $mime;

        header("Content-Description: File Transfer");
        header("Content-Type: {$mime}");
        header("Content-Disposition: attachment;filename={$saveAs}");
        header("Cache-Control: max-age=0");

        header("Content-Length: " . filesize($filename));

        ob_clean(); flush(); readfile($filename);
    }

    public function redirect(string $index, array $queryParams = [])
    {
        $queryString = empty($queryParams) ? "" : "?".http_build_query($queryParams);
        header("Location: " . BASE_URL.( ROUTE_NAMES[$index] ?? "/error").$queryString);
        die;
    }

}