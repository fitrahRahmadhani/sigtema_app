<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getWorkUnitAttribute()
    {
        if (!empty($this->section)) {
            return $this->section;
        }
        if (!empty($this->biro)) {
            return $this->biro;
        }
        if (!empty($this->department)) {
            return $this->department;
        }
        if (!empty($this->directorate)) {
            return $this->directorate;
        }
        return null;
    }
}
