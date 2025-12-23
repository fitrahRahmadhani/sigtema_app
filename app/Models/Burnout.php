<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Burnout extends Model
{
    protected $guarded = [];

    protected $casts = [
        'emotional_exhaustion' => 'integer',
        'depersonalization' => 'integer',
        'achievement_decline' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTotalScore()
    {
        return $this->emotional_exhaustion + $this->depersonalization + $this->achievement_decline;
    }
}
