<?php

namespace App\Models;

use App\Models\PsychogramResult;
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
        return $this->hasOne(Position::class, 'employee_id');
    }

    /**
     * Get the education that the employee holds.
     */
    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    // Jika perlu data yang sudah diformat
    public function getFormattedEducationsAttribute()
    {
        return $this->educations->map(function ($education) {
            return [
                'period' => $education->formatted_period,
                'degree' => $education->degree,
                'major' => $education->major,
                'institution' => $education->institution_name,
            ];
        });
    }

    /**
     * Get the technical skills that the employee holds.
     */
    public function technicalCompetencies()
    {
        return $this->hasMany(TechnicalCompetency::class);
    }

    /**
     * Get the mcu results that the employee holds.
     */
    public function mcuResults()
    {
        return $this->hasMany(Mcu::class);
    }

    /**
     * Get the position history that the employee holds.
     */
    public function positionHistories()
    {
        return $this->hasMany(PositionHistory::class);
    }

    public function psychogramResults()
    {
        return $this->hasMany(PsychogramResult::class);
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
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : "-";
    }

    /**
     * Get employee's tenure (years of service)
     */
    public function getTenureAttribute(): int
    {
        return $this->hire_date ? $this->hire_date->diffInYears(now()) : 0;
    }

    /**
     * Mendapatkan superior employee (atasan langsung)
     */
    public function getSuperiorEmployee()
    {
        if (!$this->position) {
            return null;
        }

        $superiorPosition = $this->position->getSuperiorPosition($this->company_id);

        if (!$superiorPosition) {
            return null;
        }

        return self::where('company_id', $this->company_id)
            ->where('position_id', $superiorPosition->id)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Mendapatkan subordinate employees (bawahan langsung)
     */
    public function getSubordinateEmployees()
    {
        if (!$this->position) {
            return collect();
        }

        $subordinatePositions = $this->position->getSubordinatePositions($this->company_id);

        if ($subordinatePositions->isEmpty()) {
            return collect();
        }

        return self::where('company_id', $this->company_id)
            ->whereIn('position_id', $subordinatePositions->pluck('id'))
            ->where('status', 'active')
            ->get();
    }

    public function getHiredAttribute()
    {
        return $this->hire_date ? $this->hire_date->format('d F Y') : "-";
    }

    public function getScores()
    {
        return PsychogramResult::getEmployeeScores($this->employee_number);
    }

    /**
     * Cek apakah employee adalah CEO/Top Level
     */
    public function isTopLevel(): bool
    {
        if (!$this->position) {
            return false;
        }

        return $this->position->isRootPosition($this->company_id);
    }

    /**
     * Scope untuk employee aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk filter berdasarkan company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope untuk filter berdasarkan position
     */
    public function scopeInPosition($query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }
}
