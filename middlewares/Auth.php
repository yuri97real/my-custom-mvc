<?php

namespace Middlewares;

use Core\iRequest;
use Core\iResponse;

class Auth {

    public function session(iRequest $request, iResponse $response)
    {
        $account = $_SESSION["user"] ?? [];
        if( empty($account) ) $response->redirect("login.index");
    }

}