<?php

use App\Core\iRequest;
use app\Core\iResponse;

class ErrorAPI {

    public function index(iRequest $request, iResponse $response)
    {
        $params = $request->params();
        $code = $params->code;
        $response->status($code)->json();
    }

    public function error404(iRequest $request, iResponse $response)
    {
        $response->status(404)->json();
    }

}