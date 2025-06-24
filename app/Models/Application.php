<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant',
        'application_type',
        'status',
        'message',
    ];

    public function getFinishedAttribute(): bool
    {
        return in_array($this->status, ['granted', 'denied']);
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant');
    }
}
