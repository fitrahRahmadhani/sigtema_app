<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersTemplateExport implements FromView
{
    public function view(): View
    {
        $user = Auth::user();
        $companyId = null;
        $companyName = null;
        $companies = collect();
        $roles = collect();

        if (!is_null($user->company_id)) {
            $company = Company::find($user->company_id);
            $companyId = $user->company_id;
            $companyName = $company ? $company->name : null;
        } else {
            $companies = Company::orderBy('name')->get(['id', 'name']);
            // Only superadmin gets role list in template
            $roles = Role::orderBy('name')->get(['id', 'name']);
        }

        return view('exports.users-template', [
            'companyId'    => $companyId,
            'companyName'  => $companyName,
            'isSuperadmin' => is_null($user->company_id),
            'companies'    => $companies,
            'roles'        => $roles,
        ]);
    }
}
