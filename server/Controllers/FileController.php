<?php

namespace Server\Controllers;

use Core\iRequest;
use Core\iResponse;

class FileController {

    public static function show(iRequest $request, iResponse $response)
    {
        extract($request->params);

        $filename = "{$dirname}/{$folder}/{$basename}";
        $pathinfo = pathinfo($filename);

        $extension = $pathinfo["extension"];
        $isAllowed = empty($allowed) || in_array($extension, $allowed);

        return file_exists($filename) && $isAllowed ?
            $response->file($filename) :
            $response->json(["message"=> "file not exists!"]);
    }

}