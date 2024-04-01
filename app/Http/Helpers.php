<?php

namespace App\Http;

class Helpers {
    public static function returnOkResponse($msg, $payload) 
    {
        $errorCode = 200;
        return response()->json(['msg' => $msg, 'payload' => $payload], $errorCode);
    }

    public static function returnCreatedResponse($msg, $payload) 
    {
        $errorCode = 201;
        return response()->json(['msg' => $msg, 'payload' => $payload], $errorCode);
    }   
}