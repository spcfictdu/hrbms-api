<?php

namespace App\Traits;

trait ResponseAPI
{
    protected function success($message, $data = [], $statusCode = 200){

        return response()->json([
            "message" => $message,
            "results" => $data,
            "code"    => $statusCode,
            "error"   => false
        ], $statusCode);
    }


    protected function error($message, $statusCode = 500, $data = [], $error = true){

        return response()->json([
            "message" => $message,
            "results" => $data,
            "code"    => $statusCode,
            "error"   => $error
        ], $statusCode);
    }
}
