<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function getUser() {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }
}
