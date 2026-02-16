<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = [
        'title',
        'description',
        'frecuency'
    ];

    public function user()
    {
        this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }
}
