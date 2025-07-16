<?php

namespace App\Http\Controllers;

use App\Http\Requests\CopounStoreRequest;
use App\Models\Coupon;
use App\Models\User;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    use ApiResponseTrait;
    protected notificationService $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
   public function index(): object
   {
       $coupons = Coupon::get()->toArray();
       $this->successResponse('coupon you have created', $coupons,200);
      // return view('coupons.index', compact('coupons'));
   }
   public function store(CopounStoreRequest $request): object
   {
       try{
           $couponData = $request->validated();
           $coupon = Coupon::create($couponData);
           $students = User::role('student')->get()->toArray();
           $this->successResponse($coupon,'Coupon created successfully.',200);
           $this->notificationService->sendToStudents($students,'New coupon created','You have a new coupon you can use.');
           $admin = User::role('admin')->get();
           $message = 'New coupon has been add by teacher: '.
           $this->notificationService->sendToAdmin($admin,'New coupon created',$message);
       }
       catch (\Exception $exception)
       {
           $this->errorResponse($exception->getMessage(),'error',500);

       }


   }
}
