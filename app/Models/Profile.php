<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'phone',
        'address',
        'location',
        'skills',
        'image',
        'onboarding_step',
        'completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete()
    {
        return $this->completed;
    }
}