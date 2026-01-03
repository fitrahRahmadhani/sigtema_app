<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PositionRequirementTemplateExport implements FromView
{
    public function view(): View
    {
        return view('exports.position-requirement-template');
    }
}
