<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    protected $table = 'item_tags';
    protected $with =  ['tag'];

    public function getItem() {
        return $this->belongsTo(Item::class, 'item_id', 'id')->get()->first();
    }

    public function tag() {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }
}
