<?php

namespace App\Http\Controllers;

<<<<<<< HEAD

=======
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
use App\Http\Requests\UserSigninRequest;
use App\Http\Requests\UserSignUpRequest;
use App\Response\Response;
use App\Services\UserServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
<<<<<<< HEAD

=======
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
use Throwable;

class UserController extends Controller
{
   private UserServices $userService;
   public function __construct(UserServices $userServices)
   {
       $this->userService = $userServices;
   }
   public function register(UserSignUpRequest $request): JsonResponse
   {
       $data = [];
       try {
           $data = $this->userService->register($request->validated());
<<<<<<< HEAD

=======
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
           return Response::Success($data['user'],$data['message']);
       }
       catch(Throwable $th)
       {
           $message = $th->getMessage();
           return Response::Error($data ,$message);
       }
   }

   public function login(UserSigninRequest $request): JsonResponse
   {
      $data =[];
      try{
          $data = $this->userService->login($request);
          return Response::Success($data['user'],$data['message'],$data['code']);
      }
      catch(Throwable $th)
      {
          $message = $th->getMessage();
          return Response::Error($data ,$message);
      }
   }
   public function logout(): JsonResponse
   {
       $data =[];
       try{
           $data = $this->userService->logout();
           return Response::Success($data['user'],$data['message'],$data['code']);
       }
       catch(Throwable $th)
       {
           $message = $th->getMessage();
           return Response::Error($data ,$message);
       }
   }


}
