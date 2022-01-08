<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    public function getReporter() {
        return $this->belongsTo(User::class, 'reporter', 'id')->first();
    }
    public function getVictim() {
        return $this->belongsTo(User::class, 'victim', 'id')->first();
    }

    public function resolve($time) {
        if ($time > 0) {
            $this->status = 1;
            $this->save();
            $target = $this->getVictim();
            if ($target !== null) {
                BanRecord::ban($target, $time * 60 * 60 * 24, $this->reason);
            }
        } else {
            $this->status = 2;
            $this->save();
        }
    }

    // 取得等待處理的檢舉項目
    public static function getWaitingResolveReports() {
        return Report::where('status', 0)->orderBy('updated_at', 'desc')->get();
    }
    // 取得已處理的檢舉項目
    public static function getResolvedReports() {
        return Report::where('status', '!=', 0)->orderBy('updated_at', 'desc')->get();
    }
}
