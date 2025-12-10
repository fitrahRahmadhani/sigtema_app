<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\PsychogramAspect;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PsychogramTemplateExport implements FromView
{
    public function view(): View
    {
        $user = Auth::user();
        $companyId = null;
        $companyName = null;

        if (!is_null($user->company_id)) {
            $company = Company::find($user->company_id);
            $companyId = $user->company_id;
            $companyName = $company ? $company->name : null;
        }

        // Get aspects available for this company
        $aspects = PsychogramAspect::getAvailableAspects($companyId);

        return view('exports.psychogram-template', [
            'companyId' => $companyId,
            'companyName' => $companyName,
            'isSuperadmin' => is_null($user->company_id),
            'aspects' => $aspects
        ]);
    }
}