<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'services_offered',
        'operating_hours',
        'is_verified',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'services_offered' => 'array',
        'is_verified' => 'boolean',
    ];

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class, 'shop_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'shop_id', 'user_id')
            ->withTimestamps();
    }

    public function scopeNearby($query, float $lat, float $lng, int $radiusKm = 25)
    {
        $haversine = '(6371 * acos(cos(radians(?)) 
                     * cos(radians(latitude)) 
                     * cos(radians(longitude) - radians(?)) 
                     + sin(radians(?)) 
                     * sin(radians(latitude))))';

        return $query
            ->selectRaw("*, {$haversine} AS distance", [$lat, $lng, $lat])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance');
    }

    public function scopeOfferingService($query, string $serviceType)
    {
        return $query->whereJsonContains('services_offered', $serviceType);
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}
