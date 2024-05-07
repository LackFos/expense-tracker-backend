<?php

namespace App\Http;

use App\Enums\StatusCode;
use Illuminate\Support\Facades\Log;

class Helpers {
    public static function returnOkResponse($message, $data) 
    {
        return response()->json(['success' => 'true', 'message' => $message, 'data' => $data], StatusCode::OK);
    }

    public static function returnCreatedResponse($message, $data) 
    {
        return response()->json(['success' => 'true', 'message' => $message, 'data' => $data], StatusCode::CREATED);
    } 

    public static function throwUnauthorizedError($message) {
        return response()->json(['success' => 'false', 'message' => $message, ], StatusCode::UNAUTHORIZED);
    }

    public static function throwNotFoundError($message)
    {
        return response()->json(['success' => 'false', 'message' => $message, ], StatusCode::NOT_FOUND);
    }

    public static function throwInternalError($error) {
        Log::error($error);
        return response()->json(['success' => 'false', 'message' => 'An unexpected error occurred on the server.', ], StatusCode::INTERNAL_SERVER_ERROR);
    }
}