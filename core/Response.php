<?php

namespace Core;

interface iResponse {

    public function status(int $code);
    public function view(string $view, array $data = []);
    public function json(array $body);
    public function file(string $saveAs, string $filepath, string $contentType = "application/octet-stream");
    public function redirect(string $route);

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

        $local = function (string $file) use ($version) { return APP_URL."/local/{$file}".$version; };
        $assets = function (string $file) use ($version) { return APP_URL."/assets/{$file}".$version; };
        $uploads = function (string $file) { return SERVER_URL."/uploads/{$file}"; };
        
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
        
        $component = function (string $name, array $data = []) use ($route, $local, $assets, $uploads) {
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

    public function file(string $saveAs, string $filepath, string $contentType = "application/octet-stream")
    {
        header("Content-Description: File Transfer");
        header("Content-Type: {$contentType}");
        header("Content-Disposition: attachment;filename={$saveAs}");
        header("Cache-Control: max-age=0");

        header("Content-Length: " . filesize($filepath));

        ob_clean(); flush(); readfile($filepath);
    }

    public function redirect(string $index)
    {
        header("Location: " . BASE_URL.( ROUTE_NAMES[$index] ?? "error") ); die;
    }

}