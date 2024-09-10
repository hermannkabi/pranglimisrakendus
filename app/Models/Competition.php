<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $primaryKey = 'comp_id'; // UUID
    protected $fillable = [
        'comp_name', 
        'comp_description', //optional
        "dt_start",
        "dt_end", 
        "attempt_count",
        "game_data",
        "participants", //many-to-many releation
    ];
    
    public function competitions()
    {
        return $this->belongsToMany(User::class);
    }

}
