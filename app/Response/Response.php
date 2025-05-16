<?php

namespace App\Response;

<<<<<<< HEAD
use Illuminate\Http\JsonResponse;

class Response
{
    public static function Success($data , $message, $code = 200): JsonResponse
=======
class Response
{
    public static function Success($data , $message, $code = 200): \Illuminate\Http\JsonResponse
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
    {
        return response()->json([
            'data' => $data,
            'message' => $message

        ], $code);
    }
<<<<<<< HEAD
    public static function Error($data ,$message, $code = 500): JsonResponse
    {
       return response()->json([
           'data' => $data,
           'message' => $message
       ],$code);
    }


=======
    public static function Error($data ,$message, $code = 500): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ],$code);
    }



>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
}
