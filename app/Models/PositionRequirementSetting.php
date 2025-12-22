<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PositionRequirementSetting extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'position_id',
        'position_code',
        'name',
        'value'
    ];

    /**
     * Get the position that owns the requirement
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Scope to filter by position code
     */
    public function scopeByPositionCode($query, $positionCode)
    {
        return $query->where('position_code', $positionCode);
    }

    /**
     * Scope to filter by position ID
     */
    public function scopeByPositionId($query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }
}
