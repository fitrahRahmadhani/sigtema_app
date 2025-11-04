<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    protected $guarded = [];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the company that owns the work unit.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the department that owns the work unit.
     */
    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    /**
     * Scope a query to search work units
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('work_unit_code', 'like', '%' . $search . '%')
                ->orWhereHas('company', function ($companyQuery) use ($search) {
                    $companyQuery->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('departemen', function ($deptQuery) use ($search) {
                    $deptQuery->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    /**
     * Scope a query to filter by company
     */
    public function scopeByCompany($query, $companyId)
    {
        if ($companyId) {
            return $query->where('company_id', $companyId);
        }

        return $query;
    }

    /**
     * Scope a query to filter by department
     */
    public function scopeByDepartment($query, $departmentId)
    {
        if ($departmentId) {
            return $query->where('departemen_id', $departmentId);
        }

        return $query;
    }
}
