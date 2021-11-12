<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    public function getItems() {
        return $this->hasMany(ItemTag::class, 'tag_id', 'id')->get();
    }
}
