<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Accessor untuk format tanggal
    public function getFormattedPeriodAttribute()
    {
        if (!$this->start_date) {
            return '-';
        }

        $startYear = Carbon::parse($this->start_date)->format('Y');

        if ($this->end_date) {
            $endYear = Carbon::parse($this->end_date)->format('Y');
            return "{$startYear} - {$endYear}";
        }

        return "{$startYear} - Present";
    }

    // Atau format khusus untuk Blade
    public function getDisplayPeriodAttribute()
    {
        return $this->formatted_period;
    }
}
