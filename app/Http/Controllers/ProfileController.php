<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class profileController extends Controller
{
    use ApiResponseTrait;
    
    public function updateUserProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'      => 'nullable|string|between:2,50',
                'email'     => 'nullable|email',
                'address'   => 'nullable|string|max:100',
                'gender'    => 'nullable|in:male,female',
                'birth_date' => 'nullable|date_format:Y-m-d',
                'bio'       => 'nullable|string|max:150',
                'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }
            $user = auth()->user();
            $user->name = $request->name ?? $user->name;
            if($request->email && $request->email !== $user->email){
                if (User::where('email', $request->email)->exists()) {
                    return $this->errorResponse('This email has already been taken', 500);
                }
                $user->email = $request->email;
            }
            $user->email = $user->email;
            $user->address = $request->address ?? $user->address;
            $user->gender = $request->gender ?? $user->gender;
            $user->birth_date = $request->birth_date ?? $user->birth_date;
            $user->bio = $request->bio ?? $user->bio;

            if ($request->hasFile('profile_photo_path')) {
                $NameOfPhoto = $request->file('profile_photo_path')->getClientOriginalName();
                $pathOfPhoto = $request->file('profile_photo_path')->storeAs('folderOfImages/Users', $NameOfPhoto, 'public');
                $user->profile_photo_path = $pathOfPhoto ?? $user->profile_photo_path;
            }
            $user->save();
            return $this->successResponse($user, 'User profile has been updated successfuly', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //*************************************************************************************************
    public function showUserProfile()
    {
        try {
            if (!auth()->user()) {
                return $this->errorResponse('User not authenticated', 401);
            }
            return $this->successResponse(auth()->user(), 'This is user profile', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong' . $e->getMessage(), 500);
        }
    }
}