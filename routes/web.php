<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Exports\EmployeeTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

Route::redirect('/', '/login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Career Path
    Route::get('career-path', App\Livewire\Pages\CareerPath::class)->name('career-path');

    // Vacant Positions
    Route::get('career-path/vacant-positions', App\Livewire\Pages\VacantPositions\Index::class)->name('vacant-positions.index');
    Route::get('career-path/vacant-positions/show', App\Livewire\Pages\VacantPositions\Show::class)->name('vacant-positions.show');

    // Candidates Comparison
    Route::get('career-path/candidates-comparison/index', App\Livewire\Pages\CandidatesComparison\Index::class)->name('candidates-comparison.index');
    Route::redirect('settings', 'settings/profile');

    // Employees Profile
    Route::get('career-path/employees-profile', App\Livewire\Pages\EmployeesProfile\Index::class)->name('employees-profile.index');
    Route::get('career-path/employees-profile/{employee:slug}/show', App\Livewire\Pages\EmployeesProfile\Show::class)->name('employees-profile.show');

    // Organization Structure
    Route::get('organization-structure', App\Livewire\Pages\OrgStructure\Index::class)->name('organization-structure.index');
    Route::get('organization-structure/table', App\Livewire\Pages\OrgStructure\Table::class)->name('organization-structure.table');
    Route::get('organization-structure/create', App\Livewire\Pages\OrgStructure\Create::class)->name('organization-structure.create');
    Route::get('organization-structure/{hierarchy}/edit', App\Livewire\Pages\OrgStructure\Edit::class)->name('organization-structure.edit');

    // Companies
    Route::get('companies', App\Livewire\Pages\Companies\Index::class)->name('companies.index');
    Route::get('companies/show', App\Livewire\Pages\Companies\Show::class)->name('companies.show');
    Route::get('companies/create', App\Livewire\Pages\Companies\Create::class)->name('companies.create');
    Route::get('companies/{company:slug}/edit', App\Livewire\Pages\Companies\Edit::class)->name('companies.edit');

    // Positions
    Route::get('positions', App\Livewire\Pages\Positions\Index::class)->name('positions.index');
    Route::get('positions/create', App\Livewire\Pages\Positions\Create::class)->name('positions.create');
    Route::get('positions/{position:slug}/edit', App\Livewire\Pages\Positions\Edit::class)->name('positions.edit');

    // Users
    Route::get('users', App\Livewire\Pages\Users\Index::class)->name('users.index');
    Route::get('users/create', App\Livewire\Pages\Users\Create::class)->name('users.create');
    Route::get('users/{user}/edit', App\Livewire\Pages\Users\Edit::class)->name('users.edit');

    // Roles
    Route::get('role-permissions', App\Livewire\Pages\RolePermission\Index::class)->name('role-permissions.index');
    Route::get('role-permissions/create', App\Livewire\Pages\RolePermission\Create::class)->name('role-permissions.create');
    Route::get('role-permissions/{id}/edit', App\Livewire\Pages\RolePermission\Edit::class)->name('role-permissions.edit');

    // Employees
    Route::get('employees', App\Livewire\Pages\Employees\Index::class)->name('employees.index');
    Route::get('employees/create', App\Livewire\Pages\Employees\Create::class)->name('employees.create');
    Route::get('employees/{employee:slug}/show', App\Livewire\Pages\Employees\Show::class)->name('employees.show');
    Route::get('employees/{employee:slug}/edit', App\Livewire\Pages\Employees\Edit::class)->name('employees.edit');

    // Educations
    Route::get('educations', App\Livewire\Pages\Educations\Index::class)->name('educations.index');
    Route::get('educations/create', App\Livewire\Pages\Educations\Create::class)->name('educations.create');
    Route::get('educations/{education}/edit', App\Livewire\Pages\Educations\Edit::class)->name('educations.edit');

    // MCUs
    Route::get('mcus', App\Livewire\Pages\Mcus\Index::class)->name('mcus.index');
    Route::get('mcus/create', App\Livewire\Pages\Mcus\Create::class)->name('mcus.create');
    Route::get('mcus/{mcu}/edit', App\Livewire\Pages\Mcus\Edit::class)->name('mcus.edit');

    // Technical Competencies
    Route::get('technical-competencies', App\Livewire\Pages\TechnicalCompetencies\Index::class)->name('technical-competencies.index');
    Route::get('technical-competencies/create', App\Livewire\Pages\TechnicalCompetencies\Create::class)->name('technical-competencies.create');
    Route::get('technical-competencies/{technical_competency}/edit', App\Livewire\Pages\TechnicalCompetencies\Edit::class)->name('technical-competencies.edit');

    // Position Histories
    Route::get('position-histories', App\Livewire\Pages\PositionHistories\Index::class)->name('position-histories.index');
    Route::get('position-histories/create', App\Livewire\Pages\PositionHistories\Create::class)->name('position-histories.create');
    Route::get('position-histories/{position_history}/edit', App\Livewire\Pages\PositionHistories\Edit::class)->name('position-histories.edit');

    // Innovations
    Route::get('innovations', App\Livewire\Pages\Innovations\Index::class)->name('innovations.index');

    // Bulk Upload
    Route::get('bulk-upload/employees', App\Livewire\Pages\BulkUploads\Employees::class)->name('bulk-upload.employees');
    Route::get('bulk-upload/positions', \App\Livewire\Pages\BulkUploads\Positions::class)->name('bulk-upload.positions');
    Route::get('bulk-upload/educations', \App\Livewire\Pages\BulkUploads\Educations::class)->name('bulk-upload.educations');
    Route::get('bulk-upload/mcus', \App\Livewire\Pages\BulkUploads\Mcus::class)->name('bulk-upload.mcus');
    Route::get('bulk-upload/technical-competencies', \App\Livewire\Pages\BulkUploads\TechnicalCompetencies::class)->name('bulk-upload.technical-competencies');
    Route::get('bulk-upload/position-histories', \App\Livewire\Pages\BulkUploads\PositionHistories::class)->name('bulk-upload.position-histories');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});


Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

require __DIR__ . '/auth.php';
