<?php

namespace App\Http;

class Helpers {
    public static function returnOkResponse($message, $data) 
    {
        $errorCode = 200;
        return response()->json(['success' => 'true', 'message' => $message, 'data' => $data], $errorCode);
    }

    public static function returnCreatedResponse($message, $data) 
    {
        $errorCode = 201;
        return response()->json(['success' => 'true', 'message' => $message, 'data' => $data], $errorCode);
    } 
    
    public static function throwNotFoundError($message)
    {
        $errorCode = 404;
        return response()->json(['success' => 'false', 'message' => $message, 'data' => []], $errorCode, [], JSON_FORCE_OBJECT);
    }

    public static function throwInternalError() {
        $errorCode = 500;
        return response()->json(['success' => 'false', 'message' => 'An unexpected error occurred on the server.', 'data' => []], $errorCode, [], JSON_FORCE_OBJECT);
    }
}