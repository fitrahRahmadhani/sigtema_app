<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
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
     * Get the company that owns the department.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope a query to search departments
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('company', function ($companyQuery) use ($search) {
                    $companyQuery->where('name', 'like', '%' . $search . '%');
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
}
