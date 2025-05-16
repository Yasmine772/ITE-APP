<?php

namespace App\Services;

<<<<<<< HEAD
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
=======
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
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
<<<<<<< HEAD
         Mail::to($user->email)->send(new WelcomeMail($user));

         $adminRole = Role::query()->where('name', 'admin')->first();
=======

         $adminRole = Role::query()->where('name', 'admin')->first();

>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
        if ($adminRole) {
            $user->assignRole($adminRole);

            $permissions = $adminRole->permissions->pluck('name')->toArray();
            $user->givePermissionTo($permissions);
        }


        $user->load('roles', 'permissions');

        $user = User::query()->find($user->id);
<<<<<<< HEAD
=======


>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
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


<<<<<<< HEAD
    public function logout(): \Illuminate\Http\JsonResponse
=======
    public function logout()
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
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
