<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function classes(){
        return $this->belongsToMany(Klass::class);
    }

    protected $appends = ['hasPassword'];

    public function getHasPasswordAttribute()
    {
        return !is_null($this->password);
    }

    protected $keyType = 'string'; // because id is now VARCHAR(10)
    public $incrementing = false;  // disable auto-incrementing

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //Primary information
        'id',
        'role',
        'eesnimi',
        'perenimi',
        'email',
        'password',
        'klass',
        'google_id',

        //Secondary information
        'settings',
        'profile_pic',
        'streak',
        "public_name",
    ];

    public function competitions(){
        return $this->belongsToMany(Competition::class, 'competition_user', 'user_id', 'competition_id');
    }

    public function mangs()
    {
        return $this->hasMany(Mang::class, 'user_id', 'id');
    }

    public function getDisplayNameFor(User $viewer)
    {
        if ($viewer->role === 'student' && $viewer->id !== $this->id) {
            return $this->public_name ?? 'Anonymous';
        }

        return "{$this->eesnimi} {$this->perenimi}";
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $randomId = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
            } while (User::where('id', $randomId)->exists());

            $model->id = $randomId;
        });

        static::saving(function ($user) {
            $user->eesnimi = ucfirst($user->eesnimi);
            $user->perenimi = ucfirst($user->perenimi);
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    
}
