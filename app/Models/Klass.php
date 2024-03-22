<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klass extends Model
{
    use HasFactory;

    protected $primaryKey = 'klass_id';
    protected $fillable = [
        'klass_id', // Primary key
        'klass_name', // 140.a // 140.a mata
        'klass_password',
        "teacher_id",
        "uuid", // Kasutame linkides
    ];

}
