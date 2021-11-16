<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerify extends Model
{
    protected $table = 'phone_verifies';

    public static $STATUS_BIND_PHONE = 0;
    public static $STATUS_VERIFIED_PHONE = 1;

    public function getUser() {
        return $this->belongsTo(User::class, 'user_id', 'id')->get()->first();
    }
}
