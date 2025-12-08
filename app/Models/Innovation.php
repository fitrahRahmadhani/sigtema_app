<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessors
    public function getStatusDisplayAttribute(): string
    {
        return ucfirst($this->status ?? 'unknown');
    }

    public function getReplicationDisplayAttribute(): string
    {
        return ucfirst($this->replication ?? 'unknown');
    }
}
