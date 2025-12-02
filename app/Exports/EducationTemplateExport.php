<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class EducationTemplateExport implements FromView
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

        return view('exports.education-template', [
            'companyId' => $companyId,
            'companyName' => $companyName,
            'isSuperadmin' => is_null($user->company_id)
        ]);
    }
}
