<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
   public function index()
   {
       $coupons = Coupon::get()->toArray();
       return view('coupons.index', compact('coupons'));
   }
}
