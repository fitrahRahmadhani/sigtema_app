<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'attachment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }
}
