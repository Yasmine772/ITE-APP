<?php

namespace App\Services;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;


class UserServices
{
    public function register($request): array
    {
        $user = User::query()->create([
             'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
         Mail::to($user->email)->send(new WelcomeMail($user));

         $adminRole = Role::query()->where('name', 'admin')->first();
        if ($adminRole) {
            $user->assignRole($adminRole);

            $permissions = $adminRole->permissions->pluck('name')->toArray();
            $user->givePermissionTo($permissions);
        }


        $user->load('roles', 'permissions');

        $user = User::query()->find($user->id);
        $user['token'] = $user->createToken('token')->plainTextToken;

        $message = 'User created successfully';
        return ['user' => $user, 'message' => $message];
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

        $roles = $user->getRoleNames();
        $user->role = $roles->first();

        $user['token'] = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'message' => 'User login successfully',
            'code' => 200
        ];
    }


    public function logout(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if(!is_null($user))
        {
            Auth::user()->currentAccessToken()->delete();
            $message = 'User logout successfully';
            $code = 200;
        }
        else{
            $message = 'Invalid token';
            $code = 404;
        }
        return response()->json([
            'user'=> $user,
            'message' => $message,
            'code' => $code
        ]);

    }


}
