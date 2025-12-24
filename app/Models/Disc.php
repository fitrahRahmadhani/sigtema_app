<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disc extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'assessment_date' => 'date',
        'D1' => 'decimal:1',
        'I1' => 'decimal:1',
        'S1' => 'decimal:1',
        'C1' => 'decimal:1',
        'D2' => 'decimal:1',
        'I2' => 'decimal:1',
        'S2' => 'decimal:1',
        'C2' => 'decimal:1',
        'D3' => 'decimal:1',
        'I3' => 'decimal:1',
        'S3' => 'decimal:1',
        'C3' => 'decimal:1',
    ];

    protected $attributes = [
        'is_active' => true,
        'source' => 'eksternal'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeExternal($query)
    {
        return $query->where('source', 'eksternal');
    }

    public function getDiscSet1Attribute()
    {
        return [
            'D' => $this->D1,
            'I' => $this->I1,
            'S' => $this->S1,
            'C' => $this->C1,
        ];
    }

    public function getDiscSet2Attribute()
    {
        return [
            'D' => $this->D2,
            'I' => $this->I2,
            'S' => $this->S2,
            'C' => $this->C2,
        ];
    }

    public function getDiscSet3Attribute()
    {
        return [
            'D' => $this->D3,
            'I' => $this->I3,
            'S' => $this->S3,
            'C' => $this->C3,
        ];
    }

    public function getPrimaryProfileAttribute()
    {
        $set1 = $this->disc_set1;
        arsort($set1);
        return array_key_first($set1);
    }
}
