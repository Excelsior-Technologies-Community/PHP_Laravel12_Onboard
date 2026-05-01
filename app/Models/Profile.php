<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'image',       // ✅ added
        'completed'
    ];

    // Relationship (optional but good practice)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method
    public function isComplete()
    {
        return $this->completed;
    }
}