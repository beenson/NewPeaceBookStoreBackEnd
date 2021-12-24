<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $with = ['item'];

    public function item() {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
    public function getItem() {
        return $this->belongsTo(Item::class, 'item_id', 'id')->first();
    }

    public function getOrder() {
        return $this->belongsTo(Order::class, 'order_id', 'id')->first();
    }
}
