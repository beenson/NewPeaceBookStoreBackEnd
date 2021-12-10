<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    public function getOwner() {
        return $this->belongsTo(User::class, 'owner', 'id')->first();
    }

    public function getCategory() {
        return $this->belongsTo(Category::class, 'category', 'id')->first();
    }

    public function getTags() {
        return $this->hasMany(ItemTag::class, 'item_id', 'id')->get();
    }

    public function getComments() {
        return $this->hasMany(Comment::class, 'item_id', 'id')->get();
    }
}
