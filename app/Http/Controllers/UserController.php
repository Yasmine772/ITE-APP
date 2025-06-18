<?php

namespace App\Http\Controllers;




use App\Http\Requests\UserSigninRequest;
use App\Http\Requests\UserSignUpRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Response\Response;
use App\Services\UserServices;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Support\Facades\Mail;

use Throwable;

class UserController extends Controller
{
    use ApiResponseTrait;
   private UserServices $userService;
   public function __construct(UserServices $userServices)
   {
       $this->userService = $userServices;
   }
   public function register(UserSignUpRequest $request): JsonResponse
   {
           try {
               $data = $this->userService->register($request->validated());
               return $this->successResponse($data, 'User Created Successfully');
           }
           catch (Throwable $e) {
               return $this->errorResponse($e->getMessage(), $e->getCode());
           }
   }

   public function login(UserSigninRequest $request): JsonResponse
   {
      try{
          $data = $this->userService->login($request);
          return $this->successResponse($data, 'User Login Successfully');
      }
      catch(Throwable $th)
      {
         return $this->errorResponse($th->getMessage(), $th->getCode());
      }
   }
   public function logout(): JsonResponse
   {
       try{
           $data = $this->userService->logout();
           return $this->successResponse($data, 'User Logout Successfully');
       }
       catch(Throwable $th)
       {
           return $this->errorResponse($th->getMessage(), $th->getCode());
       }
   }


public function UserForgetPassword(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
{
    $data = $request->validate([
        'email' => 'required|email|exists:users,email'
    ]);

    ResetCodePassword::where('email', $data['email'])->delete();
    //random code
    $data['code'] = mt_rand(100000, 999999);

    $codeData = ResetCodePassword::create($data);

    Mail::to($data['email'])->send(new SendCodeResetPassword($codeData->code));

    return response(['message' => trans('code.sent')], 200);

}

public function UserCheckCode(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
{
    $data = $request->validate([
        'code'=>'required|string|exists:reset_code_passwords,code'
    ]);
    $passwordReset = ResetCodePassword::query()->firstWhere('code',$data['code']);

    if ($passwordReset['created_at'] > now()->addHour()) {
        $passwordReset->delete();
        return response(['message' => trans('passwords.code_is_expire')], 422);
    }

    return response([
        'code' => $passwordReset->code,
        'message' => trans('passwords.code_is_valid')
    ], 200);
}
public function UserResetPassword(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
{
    $input = $request->validate([
        'code' => 'required|string|exists:reset_code_passwords',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // find the code
    $passwordReset = ResetCodePassword::query()->firstWhere('code', $input['code']);

    //Check if it has not expired: the time is one hour
    if ($passwordReset->created_at > now()->addHour()) {
        $passwordReset->delete();
        return response(['message' => trans('passwords.code_is_expire')], 422);
    }

    // find user's email
    $user = User::query()->firstWhere('email', $passwordReset->email);

    // update user password
    $input['password'] = bcrypt($input['password']);
    $user->update([
        'password' => $input['password'],
    ]);
    // delete current code
    $passwordReset->delete();

    return response(['message' =>'Password has been  successfully reset'], 200);
}


}
