<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fox extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< Updated upstream
        'name',
        'instagram',
        'facebook',
        'chosen_by',
    ];
=======
        'first_name',
        'last_name',
        'chosen_by',
    ];

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
>>>>>>> Stashed changes
}
