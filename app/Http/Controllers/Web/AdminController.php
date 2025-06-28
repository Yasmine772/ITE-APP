<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
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

}
