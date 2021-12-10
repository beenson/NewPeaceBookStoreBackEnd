<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payments';

    public function getOrder() {
        return $this->belongsTo(Order::class, 'order_id', 'id')->first();
    }
}
