<?php

namespace Core;

class Error {

    public function index($request, $response)
    {
        $errorFile = ROOT."/app/Views/error404.php";

        if(!file_exists($errorFile)) $this->render($errorFile);

        $response->status(404)->view("error404");
    }

    private function render(string $errorFile)
    {
        http_response_code(404);

        die("<div style='text-align: center'>
            <h1>Route Does Not Exist :(</h1>
            <p>Set a custom error page in:</p>
            <strong>{$errorFile}</strong>
            <p style='color: crimson'>Warning: this page should only be visible in development.</p>
        </div>");
    }

}