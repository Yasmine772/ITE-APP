<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class UserServices
{
    use ApiResponseTrait;
    protected NotificationService $notificationService;
    public function register($request): array
    {
        $user = User::query()->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
        $user->assignDefaultRole();
        $user->sendEmailVerificationNotification();
        $token = $user['token'] = $user->createToken('token')->plainTextToken;
        return [
            'token' => $token ,
            'user_id'=>$user->id
        ];
    }

    public function login($request): array
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return [
                'user' => null,
                'message' => 'Email or password is incorrect',
                'code' => 401
            ];
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user['token'] = $user->createToken('token')->plainTextToken;
        $message = 'User login successfully';
        return [
            'token' => $token,
            'user_id'=>$user->id ,
            'message'=> $message
        ];
    }

    public function logout(): void
    {
        $user = Auth::user();
        if (!is_null($user)) {
            $user->currentAccessToken()->delete();
        } else {
            $message = 'Invalid token';
            $code = 404;
        }
    }
}
