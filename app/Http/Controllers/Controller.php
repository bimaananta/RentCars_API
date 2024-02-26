<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function createResponseValidate($errors)
    {
        return response()->json([
            "status" => "failed",
            "message" => "Invalid Field",
            "errors" => $errors
        ], 422);
    }

    public function createResponseAPI($statusMsg, $msg, $data ,$status)
    {
        return response()->json([
            "status" => $statusMsg,
            "message" => $msg,
            "data" => $data
             
        ], $status);
    }
}
