<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'shop_id',
        'created_by',
        'service_date',
        'mileage',
        'service_type',
        'description',
        'cost',
        'receipt_path',
    ];

    protected $casts = [
        'service_date' => 'date',
        'mileage' => 'integer',
        'cost' => 'decimal:2',
    ];

    public const SERVICE_TYPES = [
        'oil_change' => 'Oil Change',
        'tire_rotation' => 'Tire Rotation',
        'brake_service' => 'Brake Service',
        'transmission' => 'Transmission',
        'engine' => 'Engine',
        'electrical' => 'Electrical',
        'air_conditioning' => 'Air Conditioning',
        'suspension' => 'Suspension',
        'inspection' => 'Inspection',
        'other' => 'Other',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function shop()
    {
        return $this->belongsTo(ServiceShop::class, 'shop_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return self::SERVICE_TYPES[$this->service_type] ?? $this->service_type;
    }

    public function hasReceipt(): bool
    {
        return ! empty($this->receipt_path);
    }
}
