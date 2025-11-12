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
}
