<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function getItems() {
        return $this->hasMany(Item::class, 'category', 'id')->get();
    }

    public static function checkDuplicateName($name) {
        return Category::where('name', $name)->get()->count() > 0;
    }
}
