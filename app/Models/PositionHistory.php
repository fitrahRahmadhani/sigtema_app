<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PositionHistory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationship
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessor untuk format tanggal
    public function getFormattedPeriodAttribute()
    {
        if (!$this->start_date) {
            return '-';
        }

        $startDate = Carbon::parse($this->start_date)->format('M Y');

        if ($this->end_date) {
            $endDate = Carbon::parse($this->end_date)->format('M Y');
            return "{$startDate} - {$endDate}";
        }

        return "{$startDate} - Present";
    }

    // Scope untuk filter company
    public function scopeCompany($query, $company)
    {
        return $query->when($company, fn($q) => $q->where('company', 'like', '%' . $company . '%'));
    }

    // Scope untuk filter work_unit
    public function scopeWorkUnit($query, $workUnit)
    {
        return $query->when($workUnit, fn($q) => $q->where('work_unit', 'like', '%' . $workUnit . '%'));
    }

    // Scope untuk filter position_name
    public function scopePositionName($query, $positionName)
    {
        return $query->when($positionName, fn($q) => $q->where('position_name', 'like', '%' . $positionName . '%'));
    }
}
