<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mang extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'user_id',
        'score_sum',
        'accuracy_sum',
        'game_count',
        'last_level',
        'time',
        'dt',
    ];
}