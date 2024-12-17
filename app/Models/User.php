<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\UserTrait;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole('Administrador') || $this->hasRole('Super Admin');
        }

        if ($panel->getId() === 'asesor') {
            return $this->hasRole('Administrador') || $this->hasRole('Asesor');
        }

        return false;
    }

    public function cotizations(): HasMany
    {
        return $this->hasMany(Cotization::class);
    }

    public function manufacturing_orders(): HasMany
    {
        return $this->hasMany(Manufacturing::class);
    }

    public function manufacturing_orders_asesor(): HasMany
    {
        return $this->hasMany(Manufacturing::class,'asesor_id');
    }

    public function manufacturigns_parts(): HasMany
    {
        return $this->hasMany(ManufacturingPart::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductWarehouse::class);
    }

}
