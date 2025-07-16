<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
         'used_count',
        'valid_from',
        'valid_until',
        'min_order_amount'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
