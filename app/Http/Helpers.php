<?php

namespace App\Http;

use App\Enums\StatusCode;
use Illuminate\Support\Facades\Log;

class Helpers {
    /**
     * Returns a JSON response with a success status and the given message and data (200).
     *
     * @param string $message The message to include in the response.
     * @param mixed|null $data The optional data to include in the response.
     * @return \Illuminate\Http\JsonResponse The JSON response with the success status, message, and data.
     */
    public static function returnOkResponse($message, $data = null) 
    {
        $response = ['success' => 'true', 'message' => $message,];

        if($data) {
            $response['data'] = $data;
        }

        return response()->json($response, StatusCode::OK);
    }

    /**
     * Returns a JSON response with a success status and the given message and data (201).
     *
     * @param string $message The message to include in the response.
     * @param mixed|null $data The optional data to include in the response.
     * @return \Illuminate\Http\JsonResponse The JSON response with the success status, message, and data.
     */
    public static function returnCreatedResponse($message, $data = null) 
    {
        $response = ['success' => 'true', 'message' => $message, 'data' => $data];

        if($data) {
            $response['data'] = $data;
        }

        return response()->json($response, StatusCode::CREATED);
    } 

    /**
     * Throws an unauthorized error with the given message (401).
     *
     * @param string $message The error message.
     * @return \Illuminate\Http\JsonResponse The JSON response with the error message and status code.
     */
    public static function throwUnauthorizedError($message) {
        $response = ['success' => 'false', 'message' => $message];
        return response()->json($response, StatusCode::UNAUTHORIZED);
    }

    /**
     * Throws a not found error with the given message (404).
     *
     * @param string $message The error message.
     * @return \Illuminate\Http\JsonResponse The JSON response with the error message and status code.
     */
    public static function throwNotFoundError($message)
    {
        $response = ['success' => 'false', 'message' => $message];
        return response()->json($response, StatusCode::NOT_FOUND);
    }

    /**
     * Throws a conflict error with the given message (409).
     *
     * @param string $message The error message.
     * @return \Illuminate\Http\JsonResponse The JSON response with the error message and status code.
     */
    public static function throwConflictError($message) {
        $response = ['success' => 'false', 'message' => $message];
        return response()->json($response, StatusCode::CONFLICT);
    }

    /**
     * Throws an internal server error with the given error message (500).
     *
     * @param mixed $error The error message or object.
     * @return \Illuminate\Http\JsonResponse The JSON response with the error message and status code.
     */
    public static function throwInternalError($error) {
        Log::debug($error);
        $response = ['success' => 'false', 'message' => 'An unexpected error occurred on the server.', ];
        return response()->json($response, StatusCode::INTERNAL_SERVER_ERROR);
    }
}