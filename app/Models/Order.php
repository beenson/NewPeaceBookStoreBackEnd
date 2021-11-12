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
}
