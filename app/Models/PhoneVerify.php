<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerify extends Model
{
    protected $table = 'phone_verifies';

    public function getUser() {
        return $this->belongsTo(User::class, 'user_id', 'id')->get()->first();
    }
}
