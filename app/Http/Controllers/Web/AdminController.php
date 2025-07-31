<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;

class AdminController extends Controller
{

    protected NotificationService  $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
   public function show(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   {
       //return view('admin.index');
   }
   public function studentShow()
   {
       $students = User::role('student')->get();
       return $students;
   }
   public function teacherShow()
   {
       $teachers = User::role('teacher')->get();
       return $teachers;
   }
     public function showAllNotification()
     {
         try{
             $admin = User::role('admin')->first();
             $notifications = $admin->notifications;
         }
         catch (\Exception $exception){
             return $exception->getMessage();
         }
         return $notifications;

     }


}
