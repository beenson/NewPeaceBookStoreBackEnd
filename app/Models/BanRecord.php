<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanRecord extends Model
{
    protected $table = 'ban_records';

    public function getUser() {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }
}
