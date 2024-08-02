<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fox extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'instagram',
        'facebook',
        'chosen_by',
    ];
}
