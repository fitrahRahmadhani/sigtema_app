<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the company that the user belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check if user has a company assigned
     */
    public function hasCompany(): bool
    {
        return !is_null($this->company_id);
    }

    /**
     * Get the user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_path) {
            return Storage::url($this->profile_path);
        }

        return null;
    }

    /**
     * Get the user's initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Scope a query to search users
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhereHas('company', function ($companyQuery) use ($search) {
                    $companyQuery->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    /**
     * Scope a query to filter by company
     */
    public function scopeByCompany($query, $companyId)
    {
        if ($companyId) {
            return $query->where('company_id', $companyId);
        }

        return $query;
    }

    /**
     * Check if user is verified
     */
    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Check if user has two factor authentication enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_confirmed_at);
    }
}
