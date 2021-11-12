<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    protected $table = 'item_tags';

    public function getItem() {
        return $this->hasOne(Item::class, 'item_id', 'id')->get();
    }

    public function getTag() {
        return $this->hasOne(Tag::class, 'tag_id', 'id')->get();
    }
}
