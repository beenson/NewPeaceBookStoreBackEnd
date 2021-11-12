<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPreview extends Model
{
    protected $table = 'item_previews';

    public function getItem() {
        return $this->belongsTo(Item::class, 'item_id', 'id')->first();
    }
}
