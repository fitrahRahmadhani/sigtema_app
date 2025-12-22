<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Papi extends Model
{
    protected $guarded = [];

    protected $casts = [
        'N' => 'integer',
        'G' => 'integer',
        'A' => 'integer',
        'L' => 'integer',
        'P' => 'integer',
        'I' => 'integer',
        'T' => 'integer',
        'V' => 'integer',
        'S' => 'integer',
        'B' => 'integer',
        'O' => 'integer',
        'X' => 'integer',
        'C' => 'integer',
        'D' => 'integer',
        'R' => 'integer',
        'Z' => 'integer',
        'E' => 'integer',
        'K' => 'integer',
        'F' => 'integer',
        'W' => 'integer',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
