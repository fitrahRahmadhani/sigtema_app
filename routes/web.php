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
