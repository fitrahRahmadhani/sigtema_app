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

    // Accessor untuk hasil MCU dengan warna
    public function getResultColorAttribute(): string
    {
        return match ($this->result) {
            'K1' => 'green',
            'K2' => 'blue',
            'K3' => 'yellow',
            'K4' => 'orange',
            'K5' => 'red',
            default => 'gray',
        };
    }

    // Accessor untuk label hasil
    public function getResultLabelAttribute(): string
    {
        return $this->result;
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
}
