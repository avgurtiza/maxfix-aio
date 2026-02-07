<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'created_by',
        'service_name',
        'reminder_type',
        'trigger_mileage',
        'trigger_days',
        'next_due_date',
        'next_due_mileage',
        'notification_methods',
        'is_active',
        'last_notified_at',
    ];

    protected $casts = [
        'next_due_date' => 'date',
        'trigger_mileage' => 'integer',
        'trigger_days' => 'integer',
        'next_due_mileage' => 'integer',
        'notification_methods' => 'array',
        'is_active' => 'boolean',
        'last_notified_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDue($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('next_due_date')
                ->where('next_due_date', '<=', now()->addDays(7));
        })->orWhere(function ($q) {
            // Mileage-based requires checking against vehicle mileage
        });
    }

    public function isDue(): bool
    {
        if ($this->next_due_date && $this->next_due_date->isPast()) {
            return true;
        }
        if ($this->next_due_mileage && $this->vehicle->current_mileage >= $this->next_due_mileage) {
            return true;
        }

        return false;
    }

    public function markCompleted(int $currentMileage): void
    {
        $this->update([
            'next_due_date' => $this->trigger_days ? now()->addDays($this->trigger_days) : null,
            'next_due_mileage' => $this->trigger_mileage ? $currentMileage + $this->trigger_mileage : null,
        ]);
    }
}
