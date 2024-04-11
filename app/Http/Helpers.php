<?php

namespace App\Http;

use App\Enums\StatusCode;

class Helpers {
    public static function returnOkResponse($message, $data) 
    {
        return response()->json(['success' => 'true', 'message' => $message, 'data' => $data], StatusCode::OK);
    }

    public static function returnCreatedResponse($message, $data) 
    {
        return response()->json(['success' => 'true', 'message' => $message, 'data' => $data], StatusCode::CREATED);
    } 
    
    public static function throwNotFoundError($message)
    {
        return response()->json(['success' => 'false', 'message' => $message, 'data' => []], StatusCode::NOT_FOUND, [], JSON_FORCE_OBJECT);
    }

    public static function throwInternalError() {
        return response()->json(['success' => 'false', 'message' => 'An unexpected error occurred on the server.', 'data' => []], StatusCode::INTERNAL_SERVER_ERROR, [], JSON_FORCE_OBJECT);
    }
}