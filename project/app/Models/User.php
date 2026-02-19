<?php

namespace App\Models;

use App\Observers\DefaultImportsObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([DefaultImportsObserver::class])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

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
            'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()) {
            'admin' => $this->is_admin ?? false,
            default => true,
        };
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(FarmSeason::class, 'owner_id', 'id');
    }

    public function farms(): HasMany
    {
        return $this->hasMany(Farm::class, 'owner_id', 'id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(FarmEmployee::class, 'owner_id', 'id');
    }

    public function plantProtectionProducts(): HasMany
    {
        return $this->hasMany(FarmPlantProtection::class, 'owner_id', 'id');
    }

    public function crops(): HasMany
    {
        return $this->hasMany(FarmCrop::class, 'owner_id', 'id');
    }

    public function fertilizers(): HasMany
    {
        return $this->hasMany(FarmFertilizer::class, 'owner_id', 'id');
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(FarmAgricultureEquipment::class, 'owner_id', 'id');
    }
}
