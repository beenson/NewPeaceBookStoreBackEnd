<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    protected $table = 'item_tags';

    public function getItem() {
        return $this->belongsTo(Item::class, 'item_id', 'id')->get()->first();
    }

    public function getTag() {
        return $this->belongsTo(Tag::class, 'tag_id', 'id')->get()->first();
    }
}
