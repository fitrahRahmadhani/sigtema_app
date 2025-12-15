<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsychogramResult extends Model
{
    protected $guarded = [];

    protected $casts = [
        'score' => 'integer'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function aspect()
    {
        return $this->belongsTo(PsychogramAspect::class);
    }

    public static function getEmployeeScores($employeeCode)
    {
        $employee = Employee::where('employee_number', $employeeCode)->first();

        if (!$employee) {
            return null;
        }

        $aspectIds = PsychogramAspect::getAvailableAspects($employee->company_id)
            ->pluck('id');

        $results = self::with('aspect')
            ->where('employee_id', $employee->id)
            ->whereIn('aspect_id', $aspectIds)
            ->get();

        if ($results->isEmpty()) {
            return [
                'strengths' => collect(),
                'weaknesses' => collect(),
            ];
        }

        $sorted = $results->sortByDesc('score');

        return [
            'strengths' => $sorted->take(3)->values(),
            'weaknesses' => $sorted->reverse()->take(3)->values(),
        ];
    }
}
