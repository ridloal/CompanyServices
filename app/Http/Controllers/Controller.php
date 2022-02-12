<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function responseTemplate($data, $message, $status) {
        return [
            'data' => $data,
            'message' => $message,
            'status' => $status
        ];
    }
}
