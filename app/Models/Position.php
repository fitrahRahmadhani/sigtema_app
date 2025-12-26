<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $guarded = [];

    public function requirements()
    {
        return $this->hasMany(PositionRequirementSetting::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relasi ke EmployeeHierarchy sebagai position
     */
    public function hierarchyAsPosition()
    {
        return $this->hasMany(EmployeeHierarchy::class, 'position_id');
    }

    /**
     * Relasi ke EmployeeHierarchy sebagai superior
     */
    public function hierarchyAsSuperior()
    {
        return $this->hasMany(EmployeeHierarchy::class, 'superior_id');
    }

    /**
     * Mendapatkan superior position
     */
    public function getSuperiorPosition($companyId = null)
    {
        $query = $this->hierarchyAsPosition();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $hierarchy = $query->with('superior')->first();

        return $hierarchy ? $hierarchy->superior : null;
    }

    /**
     * Mendapatkan subordinate positions
     */
    public function getSubordinatePositions($companyId = null)
    {
        $query = $this->hierarchyAsSuperior();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->with('position')->get()->pluck('position');
    }

    /**
     * Cek apakah position ini adalah root (tidak punya superior)
     */
    public function isRootPosition($companyId = null): bool
    {
        $query = $this->hierarchyAsPosition();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->doesntExist();
    }

    /**
     * Cek apakah position ini punya subordinates
     */
    public function hasSubordinates($companyId = null): bool
    {
        $query = $this->hierarchyAsSuperior();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->exists();
    }

    /**
     * Mendapatkan level/tingkat position dalam hierarchy
     */
    public function getHierarchyLevel($companyId): int
    {
        $level = 0;
        $currentPosition = $this;

        while ($superior = $currentPosition->getSuperiorPosition($companyId)) {
            $level++;
            $currentPosition = $superior;

            // Prevent infinite loop
            if ($level > 10) break;
        }

        return $level;
    }

    /**
     * Scope untuk filter berdasarkan company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function getWorkUnitAttribute()
    {
        // Jika kolom work_unit sudah diisi, gunakan nilai tersebut
        if (!empty($this->attributes['work_unit'])) {
            return $this->attributes['work_unit'];
        }

        // Fallback ke logic lama jika work_unit kosong
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
    }
}
