<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalCompetency extends Model
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

    // Scope untuk filter issued_by
    public function scopeIssuedBy($query, $issuedBy)
    {
        return $query->when($issuedBy, fn($q) => $q->where('issued_by', 'like', '%' . $issuedBy . '%'));
    }

    // Scope untuk filter name
    public function scopeName($query, $name)
    {
        return $query->when($name, fn($q) => $q->where('name', 'like', '%' . $name . '%'));
    }
}
