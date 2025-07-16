<?php

namespace App\Http\Controllers;

use App\Http\Requests\CopounStoreRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
   public function index(): object
   {
       $coupons = Coupon::get()->toArray();
       return view('coupons.index', compact('coupons'));
   }
   public function store(CopounStoreRequest $request): object
   {

   }
}
