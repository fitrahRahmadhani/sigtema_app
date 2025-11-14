<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHierarchy extends Model
{
    protected $guarded = [];
    /**
     * Get the company that owns the hierarchy.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the position (subordinate) in this hierarchy.
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /**
     * Get the superior position in this hierarchy.
     */
    public function superior()
    {
        return $this->belongsTo(Position::class, 'superior_id');
    }

    /**
     * Scope untuk filter berdasarkan company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope untuk mendapatkan direct subordinates dari position
     */
    public function scopeSubordinatesOf($query, $positionId)
    {
        return $query->where('superior_id', $positionId);
    }

    /**
     * Scope untuk mendapatkan superior dari position
     */
    public function scopeSuperiorOf($query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    /**
     * Helper method untuk mendapatkan semua subordinates (recursive)
     */
    public static function getAllSubordinates($positionId, $companyId)
    {
        $subordinates = self::where('company_id', $companyId)
            ->where('superior_id', $positionId)
            ->with('position')
            ->get();

        $allSubordinates = collect();

        foreach ($subordinates as $subordinate) {
            $allSubordinates->push($subordinate);
            $children = self::getAllSubordinates($subordinate->position_id, $companyId);
            $allSubordinates = $allSubordinates->merge($children);
        }

        return $allSubordinates;
    }

    /**
     * Helper method untuk mendapatkan hierarchy path dari root ke position
     */
    public static function getHierarchyPath($positionId, $companyId)
    {
        $path = collect();
        $currentPositionId = $positionId;

        while ($currentPositionId) {
            $hierarchy = self::where('company_id', $companyId)
                ->where('position_id', $currentPositionId)
                ->with('position')
                ->first();

            if ($hierarchy) {
                $path->prepend($hierarchy);
                $currentPositionId = $hierarchy->superior_id;
            } else {
                break;
            }
        }

        return $path;
    }

    /**
     * Helper method untuk validasi circular reference
     */
    public static function hasCircularReference($positionId, $superiorId, $companyId)
    {
        $path = self::getHierarchyPath($superiorId, $companyId);
        return $path->pluck('position_id')->contains($positionId);
    }
}
