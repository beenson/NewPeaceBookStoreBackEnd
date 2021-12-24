<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    public static $ADMIN = 1;
    public static $BANNED = -1;
    public static $NORMAL = 0;
    public static $PUBLISHING_HOUSE = 0;
    protected $with =  ['items', 'comments', 'banRecords', 'major', 'banRecords'];

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

    public function items() {
        return $this->hasMany(Item::class, 'owner', 'id');
    }
    public function getItems() {
        return $this->hasMany(Item::class, 'owner', 'id')->orderBy('updated_at', 'desc')->get();
    }

    // 個人發出的評論
    public function comments() {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
    public function getComments() {
        return $this->hasMany(Comment::class, 'user_id', 'id')->orderBy('updated_at', 'desc')->get();
    }

    // 商店的評論
    public function getMerchantComments() {
        return $this->hasMany(Comment::class, 'merchant_id', 'id')->orderBy('updated_at', 'desc')->get();
    }

    public function orders() {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
    public function getOrders() {
        return $this->hasMany(Order::class, 'user_id', 'id')->orderBy('updated_at', 'desc')->get();
    }

    public function banRecords() {
        return $this->hasMany(BanRecord::class, 'user_id', 'id');
    }
    public function getBanRecords() {
        return $this->hasMany(BanRecord::class, 'user_id', 'id')->orderBy('updated_at', 'desc')->get();
    }

    public function getMessages(User $targetUser) {
        return Message::where('from_user', $this->id)->where('to_user', $targetUser->id)->orWhere(function ($query) use ($targetUser) {
            $query->where('to_user', $this->id)->where('from_user', $targetUser->id);
        })->get();
    }

    public function getPhoneVerify() {
        return $this->hasOne(PhoneVerify::class, 'user_id', 'id')->get()->first();
    }

    public function major() {
        return $this->belongsTo(Category::class, 'major', 'id');
    }
    public function getMajor() {
        return $this->belongsTo(Category::class, 'major', 'id')->get()->first();
    }

    public function checkCommentAvailible($orderId) {
        $order = Order::find($orderId);
        if ($order === null) {
            return false;
        }
        if ($order->user_id !== $this->id) {
            return false;
        }
        return $order->getComment() === null;
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
