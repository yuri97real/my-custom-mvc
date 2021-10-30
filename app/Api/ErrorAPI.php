<?php

use App\Core\Controller;

class ErrorAPI extends Controller {

    public function default()
    {
        $this->response(403);
    }

}