<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    public function updateUserProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name'      =>'nullable|string|between:2,50',
            'email'     =>'nullable|email|unique:users',
            'address'   =>'nullable|string|max:100',
            'image'     =>'nullable|image',
            'gender'    =>'nullable|in:male,female',
            'brith_date'=>'nullable|date_format:Y-m-d',
            'bio'       =>'nullable|string|max:150',
        ]);
        if($validator->fails()){
            return response()->json([$validator->errors()],400);
        }

        $user = auth()->user();
        if(!$user){
            return response()->json(['User not found!'],404);
        }

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->address = $request->address ?? $user->address;
        $user->gender = $request->gender ?? $user->gender;
        $user->brith_date = $request->brith_date ?? $user->brith_date;
        $user->bio = $request->bio ?? $user->bio;

        if($request->hasFile('image')){
            $NameOfPhoto = $request->file('image')->getClientOriginalName();
            $pathOfPhoto = $request->file('image')->storeAs('folderOfImages/Users', $NameOfPhoto ,'public');
            $user->image = $pathOfPhoto ?? $user->image;
        }
        $user->save();

        return response()->json([
            'message' => 'User profile has been updated successfuly',
            'data' => $user,
        ],200);
    }
//*************************************************************************************************
    public function showUserProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' =>  auth()->user()
        ],200);
    }
}
