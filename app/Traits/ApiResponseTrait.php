<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Success response
     */
    protected function successResponse($data = [], $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Error response
     */
    protected function errorResponse($message = 'Error', $error = null, $code = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'error' => $error,
        ], $code);
    }
}
