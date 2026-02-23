<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'vin',
        'make',
        'model',
        'year',
        'current_plate',
        'current_mileage',
        'color',
        'fuel_type',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'current_mileage' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($vehicle) {
            if (empty($vehicle->uuid)) {
                $vehicle->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'vehicle_user')
            ->withPivot('relationship', 'is_primary')
            ->withTimestamps();
    }

    public function owners()
    {
        return $this->users()->wherePivot('relationship', 'owner');
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class)->orderByDesc('service_date');
    }

    public function reminders()
    {
        return $this->hasMany(MaintenanceReminder::class);
    }

    public function activeReminders()
    {
        return $this->reminders()->where('is_active', true);
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->year} {$this->make} {$this->model}";
    }

    public function updateMileage(int $mileage): void
    {
        if ($mileage > $this->current_mileage) {
            $this->update(['current_mileage' => $mileage]);
        }
    }
}
