<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    protected $fillable = [
        'completed_on'
    ];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
