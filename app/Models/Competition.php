<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $primaryKey = 'competition_id';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'competition_id',
        'name', 
        'description',
        "dt_start",
        "dt_end", 
        "attempt_count",
        "game_data",
        "public",
        "created_by",
    ];

    protected $appends = ['active'];
    
    
    public function participants()
    {
        return $this->belongsToMany(
            User::class,
            'competition_user',   // Pivot table name
            'competition_id',     // Foreign key on pivot for this model
            'user_id'             // Foreign key on pivot for related model
        );
    }

    public function getActiveAttribute()
    {
        $now = now();
        return $this->dt_start < $now && $now < $this->dt_end;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($competition) {
            if (empty($competition->competition_id)) {
                $competition->competition_id = (string) mt_rand(100000000000000000, 999999999999999999);
            }
        });
    }

}
