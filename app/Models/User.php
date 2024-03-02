<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //Primary information
        'id',
        'eesnimi',
        'perenimi',
        'email',
        'password',
        'klass',
        'techer',
        'google_id',

        //User settings
        'dark_backround',
        'visible_timer',
        'score_animations',
        'default_time',
        'color',

        //Game information
        'score_sum',
        'experience',
        'accuracy_sum',
        'game_count',
        'last_level',
        'last_equation',
        'time',
        'dt',
        'mistakes_tendency',
        'mistakes_sum',

        //Quests
        'quests',
        'quest_type',
        'completed_quests_sum',
    ];

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
