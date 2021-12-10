<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function getUser() {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    public function getOrderItems() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')->get();
    }

    public function getOrderPayment() {
        return $this->hasOne(OrderPayment::class, 'order_id', 'id')->get()->first();
    }

    public static function getMerchantOrders($uid, $onProcess = true) {
        $query = Order::where('merchant_id', $uid);
        if ($onProcess) {
            $query = $query->where('status', 0);
        } else {
            $query = $query->where('status', '!=', 0);
        }
        return $query->orderBy('id', 'desc')->get();
    }
}
