<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public function getSender() {
        return $this->belongsTo(User::class, 'from_user', 'id')->first();
    }

    public function getReciever() {
        return $this->belongsTo(User::class, 'to_user', 'id')->first();
    }
}
