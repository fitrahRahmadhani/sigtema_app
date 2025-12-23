<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PositionRequirementTemplateExport implements FromView
{
    public function view(): View
    {
        $user = Auth::user();
        $companyId = null;

        if (!is_null($user->company_id)) {
            $companyId = $user->company_id;
        }

        return view('exports.position-requirement-template', [
            'companyId' => $companyId,
            'isSuperadmin' => is_null($user->company_id)
        ]);
    }
}
