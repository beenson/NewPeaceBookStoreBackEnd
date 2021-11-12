<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    public static function checkAvailible($email, $sid) {
        $user = User::where('email', $email)->first();
        if ($user !== null) {
            return false;
        }
        $user = User::where('sid', $sid)->first();
        if ($user !== null) {
            return false;
        }
        return true;
    }

    public function getItems() {
        return $this->hasMany(Item::class, 'owner', 'id')->get();
    }

    public function getComments() {
        return $this->hasMany(Comment::class, 'user_id', 'id')->get();
    }

    public function getOrders() {
        return $this->hasMany(Order::class, 'user_id', 'id')->get();
    }

    public function getBanRecords() {
        return $this->hasMany(BanRecord::class, 'user_id', 'id')->get();
    }

    public function getMessages(User $targetUser) {
        return Message::where('from_user', $this->id)->where('to_user', $targetUser->id)->orWhere(function ($query) use ($targetUser) {
            $query->where('to_user', $this->id)->where('from_user', $targetUser->id);
        })->get();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
