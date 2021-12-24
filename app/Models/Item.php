<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $with =  ['owner', 'category', 'tags', 'images'];

    public function getOwner() {
        return $this->belongsTo(User::class, 'owner', 'id')->first();
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner', 'id');
    }

    public function getCategory() {
        return $this->belongsTo(Category::class, 'category', 'id')->first();
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    public function getTags() {
        return $this->hasMany(ItemTag::class, 'item_id', 'id')->get();
    }

    public function tags() {
        return $this->hasMany(ItemTag::class, 'item_id', 'id');
    }

    public function getImages() {
        return $this->hasMany(ItemPreview::class, 'item_id', 'id')->get();
    }

    public function images() {
        return $this->hasMany(ItemPreview::class, 'item_id', 'id');
    }
}
