<?php

namespace App\Models;

use App\Livewire\Pages\BulkUploads\PsychogramResults;
use Illuminate\Database\Eloquent\Model;

class PsychogramAspect extends Model
{
    protected $fillable = [
        'company_id',
        'aspect_code',
        'aspect_name',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function results()
    {
        return $this->hasMany(PsychogramResult::class);
    }

    public function scopeForCompany($query, $companyId = null)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('company_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scores()
    {
        // Pastikan ada model PsychogramResult jika digunakan
        return $this->hasMany(PsychogramResult::class, 'aspect_id');
    }

    /**
     * Get aspects available for a specific company
     * Includes global aspects and company-specific aspects
     */
    public static function getAvailableAspects($companyId = null)
    {
        return self::where(function ($query) use ($companyId) {
            $query->where('company_id', $companyId)
                ->orWhereNull('company_id');
        })
            ->active()
            ->orderBy('company_id') // Global aspects first
            ->orderBy('sort_order')
            ->get();
    }
}
