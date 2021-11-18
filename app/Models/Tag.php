<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    public function getItems() {
        return $this->belongsToMany(Item::class, 'item_tags', 'item_id', 'tag_id')->get();
    }

    public function checkDuplicateName($name) {
        return Tag::where('name', $name)->get()->count() > 0;
    }
}
