<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PositionTemplateExport implements FromView
{
    public function view(): View
    {
        $user = Auth::user();
        $companyId = null;
        $companyName = null;
        $employees = collect();

        if (!is_null($user->company_id)) {
            $company = Company::find($user->company_id);
            $companyId = $user->company_id;
            $companyName = $company ? $company->name : null;
            $employees = Employee::where('company_id', $companyId)->get(['id', 'employee_number', 'name']);
        } else {
            $employees = Employee::all(['id', 'employee_number', 'name', 'company_id']);
        }

        return view('exports.position-template', [
            'companyId' => $companyId,
            'companyName' => $companyName,
            'employees' => $employees,
            'isSuperadmin' => is_null($user->company_id)
        ]);
    }
}
