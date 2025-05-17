<?php

namespace App\Response;
class Response
{
    public static function Success($data , $message, $code = 200): \Illuminate\Http\JsonResponse

    {
        return response()->json([
            'data' => $data,
            'message' => $message

        ], $code);
    }

    public static function Error($data ,$message, $code = 500): \Illuminate\Http\JsonResponse
    {
       return response()->json([
           'data' => $data,
           'message' => $message
       ],$code);
    }


}
