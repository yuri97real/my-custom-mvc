<?php

namespace App\Controllers;

use Core\iRequest;
use Core\iResponse;

class FileController {

    private function forceCache()
    {
        session_cache_limiter('public');
        session_start();
    }

    public static function show(iRequest $request, iResponse $response)
    {
        self::forceCache();
        extract($request->params);

        $filename = str_replace("@", ".", "{$dirname}/{$folder}/{$basename}");
        $pathinfo = pathinfo($filename);

        $extension = $pathinfo["extension"];
        $isAllowed = empty($allowed) || in_array($extension, $allowed);

        return file_exists($filename) && $isAllowed ?
            $response->file($filename) :
            $response->view("error/index", ["title"=> "Ooops!"], "default");
    }

}