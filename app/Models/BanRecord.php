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

    public static function ban(User $user, $time, $reason) {
        $record = new BanRecord;
        $record->user_id = $user->id;
        $record->reason = $reason;
        $record->duration = date('Y-m-d H:i:s', time() + $time);
        $record->save();
        return $record;
    }
}
