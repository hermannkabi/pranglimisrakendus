<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mang extends Model
{
    use HasFactory;
    protected $primaryKey = 'game_id';
    public $timestamps = false;
    protected $fillable = [
        'game_id',
        'game', //korrutamine, liitmine
        'game_type', //täisarvud, naturaalarvud
        'user_id',
        'score_sum',
        'experience',
        // Ainult selle mängu täpsus
        'accuracy_sum',
        'game_count',
        'last_level',
        'last_equation',
        'time',
        'dt',
        'log',
    ];
}
