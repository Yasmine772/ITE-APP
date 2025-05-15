<?php

namespace App\Response;

use Illuminate\Http\JsonResponse;

class Response
{
    public static function Success($data , $message, $code = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message

        ], $code);
    }
    public static function Error($data ,$message, $code = 500): JsonResponse
    {
       return response()->json([
           'data' => $data,
           'message' => $message
       ],$code);
    }


}
