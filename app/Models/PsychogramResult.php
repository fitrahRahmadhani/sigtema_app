<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsychogramResult extends Model
{
    protected $guarded = [];

    protected $casts = [
        'score' => 'integer'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function aspect()
    {
        return $this->belongsTo(PsychogramAspect::class);
    }
}
