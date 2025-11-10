<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

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
    Route::get('career-path/employees-profile/show', App\Livewire\Pages\EmployeesProfile\Show::class)->name('employees-profile.show');

    // Organization Structure
    Route::get('organization-structure', App\Livewire\Pages\OrgStructure\Index::class)->name('organization-structure.index');

    // Companies
    Route::get('companies', App\Livewire\Pages\Companies\Index::class)->name('companies.index');
    Route::get('companies/show', App\Livewire\Pages\Companies\Show::class)->name('companies.show');
    Route::get('companies/create', App\Livewire\Pages\Companies\Create::class)->name('companies.create');
    Route::get('companies/{company:slug}/edit', App\Livewire\Pages\Companies\Edit::class)->name('companies.edit');

    // Departments
    Route::get('departments', App\Livewire\Pages\Departments\Index::class)->name('departments.index');
    Route::get('departments/create', App\Livewire\Pages\Departments\Create::class)->name('departments.create');
    Route::get('departments/{department:slug}/edit', App\Livewire\Pages\Departments\Edit::class)->name('departments.edit');

    // Work Units
    Route::get('work-units', App\Livewire\Pages\WorkUnits\Index::class)->name('work-units.index');
    Route::get('work-units/create', App\Livewire\Pages\WorkUnits\Create::class)->name('work-units.create');
    Route::get('work-units/{workUnit:slug}/edit', App\Livewire\Pages\WorkUnits\Edit::class)->name('work-units.edit');

    // Positions
    Route::get('positions', App\Livewire\Pages\Positions\Index::class)->name('positions.index');
    Route::get('positions/create', App\Livewire\Pages\Positions\Create::class)->name('positions.create');
    Route::get('positions/{position:slug}/edit', App\Livewire\Pages\Positions\Edit::class)->name('positions.edit');

    Route::get('users', App\Livewire\Pages\Users\Index::class)->name('users.index');
    Route::get('users/create', App\Livewire\Pages\Users\Create::class)->name('users.create');
    Route::get('users/{user}/edit', App\Livewire\Pages\Users\Edit::class)->name('users.edit');

    Route::get('role-permissions', App\Livewire\Pages\RolePermission\Index::class)->name('role-permissions.index');
    Route::get('role-permissions/create', App\Livewire\Pages\RolePermission\Create::class)->name('role-permissions.create');
    Route::get('role-permissions/{id}/edit', App\Livewire\Pages\RolePermission\Edit::class)->name('role-permissions.edit');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

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
