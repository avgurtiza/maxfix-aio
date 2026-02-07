<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_user')
            ->withPivot('relationship', 'is_primary')
            ->withTimestamps();
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class, 'created_by');
    }

    public function favoriteShops()
    {
        return $this->belongsToMany(ServiceShop::class, 'user_favorites', 'user_id', 'shop_id')
            ->withTimestamps();
    }

    public function isFleetManager(): bool
    {
        return $this->role === 'fleet_manager';
    }

    public function isServicePersonnel(): bool
    {
        return $this->role === 'service_personnel';
    }

    public function canManageVehicle(Vehicle $vehicle): bool
    {
        return $this->vehicles()->where('vehicle_id', $vehicle->id)->exists();
    }
}
