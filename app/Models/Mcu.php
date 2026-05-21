<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mcu extends Model
{
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scope untuk filter tahun
    public function scopeYear($query, $year)
    {
        return $query->when($year, fn($q) => $q->where('year', $year));
    }

    // Scope untuk filter result
    public function scopeResult($query, $result)
    {
        return $query->when($result, fn($q) => $q->where('result', $result));
    }

    // Cast result to float for proper handling
    protected function casts(): array
    {
        return [
            'result' => 'float',
        ];
    }
}
