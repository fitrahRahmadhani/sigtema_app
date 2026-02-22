<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PsychogramAspectTemplateExport implements FromView
{
    public function view(): View
    {
        $user = Auth::user();
        $companyId = null;
        $companyName = null;
        $companies = collect();

        if (!is_null($user->company_id)) {
            $company = Company::find($user->company_id);
            $companyId = $user->company_id;
            $companyName = $company ? $company->name : null;
        } else {
            // Superadmin: provide full company list as reference in template
            $companies = Company::orderBy('name')->get(['id', 'name']);
        }

        return view('exports.psychogram-aspect-template', [
            'companyId'    => $companyId,
            'companyName'  => $companyName,
            'isSuperadmin' => is_null($user->company_id),
            'companies'    => $companies,
        ]);
    }
}
