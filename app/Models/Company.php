<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function admin()
    {
        return $this->hasMany(User::class);
    }

    public function getAdminLabelAttribute(): string
    {
        $admins = $this->hasMany(User::class, 'company_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

        if ($admins->isEmpty()) {
            return 'No admin assigned';
        }

        if ($admins->count() === 1) {
            return $admins->first()->name;
        }

        return $admins->first()->name . ' +' . ($admins->count() - 1) . ' others';
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function getEmployeesCountAttribute(): int
    {
        return $this->employees()->count();
    }
}
