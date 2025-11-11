<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'hire_date' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the company that owns the employee.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the position that the employee holds.
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the employee's profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_path) {
            return Storage::url($this->profile_path);
        }

        return null;
    }

    /**
     * Get the employee's initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Scope a query to search employees
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('employee_number', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhereHas('company', function ($companyQuery) use ($search) {
                    $companyQuery->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('position', function ($posQuery) use ($search) {
                    $posQuery->where('name', 'like', '%' . $search . '%');
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
     * Scope a query to filter by position
     */
    public function scopeByPosition($query, $positionId)
    {
        if ($positionId) {
            return $query->where('position_id', $positionId);
        }

        return $query;
    }

    /**
     * Scope a query to filter by gender
     */
    public function scopeByGender($query, $gender)
    {
        if ($gender) {
            return $query->where('gender', $gender);
        }

        return $query;
    }

    /**
     * Get employee's age
     */
    public function getAgeAttribute(): int
    {
        return $this->birth_date ? $this->birth_date->age : 0;
    }

    /**
     * Get employee's tenure (years of service)
     */
    public function getTenureAttribute(): int
    {
        return $this->hire_date ? $this->hire_date->diffInYears(now()) : 0;
    }
}
