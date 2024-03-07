<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klass extends Model
{
    use HasFactory;

    protected $fillable = [
        'klass_id',
        'klass_name',
        'klass_password',
        'student_list', //TODO: Tee ainult queridega.
        'teacher', //TODO: Tee ainult queridega. Ühenda User modeliga.
    ];

}
