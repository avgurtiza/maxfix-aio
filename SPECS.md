# MaxFix Technical Specifications

> **Purpose**: This document provides detailed specifications for implementing MaxFix. Each section includes exact file paths, code patterns, and acceptance criteria so any LLM can implement without ambiguity.

---

## Database Schema

### Users Table
**File**: `backend/database/migrations/0001_01_01_000000_create_users_table.php` (modify existing)

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['car_owner', 'fleet_manager', 'service_personnel'])->default('car_owner');
    $table->rememberToken();
    $table->timestamps();
});
```

---

### Vehicles Table
**File**: `backend/database/migrations/2024_01_02_000001_create_vehicles_table.php` (new)

```php
Schema::create('vehicles', function (Blueprint $table) {
    $table->id();
    $table->uuid('uuid')->unique();
    $table->string('vin', 17)->nullable()->unique();
    $table->string('make', 50);           // e.g., "Toyota"
    $table->string('model', 50);          // e.g., "Camry"
    $table->year('year');                 // e.g., 2020
    $table->string('current_plate', 20)->nullable();
    $table->unsignedInteger('current_mileage')->default(0);
    $table->string('color', 30)->nullable();
    $table->string('fuel_type', 20)->nullable(); // gasoline, diesel, electric, hybrid
    $table->text('notes')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

---

### Vehicle-User Pivot Table
**File**: `backend/database/migrations/2024_01_02_000002_create_vehicle_user_table.php` (new)

```php
Schema::create('vehicle_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
    $table->enum('relationship', ['owner', 'manager', 'driver'])->default('owner');
    $table->boolean('is_primary')->default(false);
    $table->timestamps();
    
    $table->unique(['user_id', 'vehicle_id']);
});
```

---

### Service Records Table
**File**: `backend/database/migrations/2024_01_02_000003_create_service_records_table.php` (new)

```php
Schema::create('service_records', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
    $table->foreignId('shop_id')->nullable()->constrained('service_shops')->nullOnDelete();
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->date('service_date');
    $table->unsignedInteger('mileage');
    $table->enum('service_type', [
        'oil_change',
        'tire_rotation',
        'brake_service',
        'transmission',
        'engine',
        'electrical',
        'air_conditioning',
        'suspension',
        'inspection',
        'other'
    ]);
    $table->text('description')->nullable();
    $table->decimal('cost', 10, 2)->nullable();
    $table->string('receipt_path')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

---

### Maintenance Reminders Table
**File**: `backend/database/migrations/2024_01_02_000004_create_maintenance_reminders_table.php` (new)

```php
Schema::create('maintenance_reminders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->string('service_name', 100);   // e.g., "Oil Change"
    $table->enum('reminder_type', ['mileage', 'date', 'both'])->default('both');
    $table->unsignedInteger('trigger_mileage')->nullable();  // e.g., 5000 (every 5000 km)
    $table->unsignedInteger('trigger_days')->nullable();     // e.g., 180 (every 6 months)
    $table->date('next_due_date')->nullable();
    $table->unsignedInteger('next_due_mileage')->nullable();
    $table->json('notification_methods')->default('["email"]'); // ["email", "push"]
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_notified_at')->nullable();
    $table->timestamps();
});
```

---

### Service Shops Table
**File**: `backend/database/migrations/2024_01_02_000005_create_service_shops_table.php` (new)

```php
Schema::create('service_shops', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->text('address');
    $table->string('city', 50);
    $table->string('postal_code', 20)->nullable();
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->string('phone', 20)->nullable();
    $table->string('email', 100)->nullable();
    $table->string('website')->nullable();
    $table->json('services_offered')->nullable(); // ["oil_change", "brakes", "tires"]
    $table->text('operating_hours')->nullable();
    $table->boolean('is_verified')->default(false);
    $table->timestamps();
});
```

---

### User Favorites Table
**File**: `backend/database/migrations/2024_01_02_000006_create_user_favorites_table.php` (new)

```php
Schema::create('user_favorites', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('shop_id')->constrained('service_shops')->cascadeOnDelete();
    $table->timestamps();
    
    $table->unique(['user_id', 'shop_id']);
});
```

---

## Models

### User Model
**File**: `backend/app/Models/User.php` (modify existing)

```php
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

    // Relationships
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

    // Helpers
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
```

---

### Vehicle Model
**File**: `backend/app/Models/Vehicle.php` (new)

```php
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

    // Use UUID for route binding
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // Relationships
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

    // Helpers
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
```

---

### ServiceRecord Model
**File**: `backend/app/Models/ServiceRecord.php` (new)

```php
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

    // Relationships
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

    // Helpers
    public function getServiceTypeLabelAttribute(): string
    {
        return self::SERVICE_TYPES[$this->service_type] ?? $this->service_type;
    }

    public function hasReceipt(): bool
    {
        return !empty($this->receipt_path);
    }
}
```

---

### MaintenanceReminder Model
**File**: `backend/app/Models/MaintenanceReminder.php` (new)

```php
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

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
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

    // Helpers
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
```

---

### ServiceShop Model
**File**: `backend/app/Models/ServiceShop.php` (new)

```php
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

    // Relationships
    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class, 'shop_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'shop_id', 'user_id')
            ->withTimestamps();
    }

    // Scopes
    public function scopeNearby($query, float $lat, float $lng, int $radiusKm = 25)
    {
        // Haversine formula for distance calculation
        $haversine = "(6371 * acos(cos(radians(?)) 
                     * cos(radians(latitude)) 
                     * cos(radians(longitude) - radians(?)) 
                     + sin(radians(?)) 
                     * sin(radians(latitude))))";

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

    // Helpers
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}
```

---

## API Controllers

### AuthController
**File**: `backend/app/Http/Controllers/Api/AuthController.php` (new)

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['sometimes', 'in:car_owner,fleet_manager'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'car_owner',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * POST /api/login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * POST /api/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * GET /api/user
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
```

---

### VehicleController
**File**: `backend/app/Http/Controllers/Api/VehicleController.php` (new)

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\VinDecoderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleController extends Controller
{
    public function __construct(
        private VinDecoderService $vinDecoder
    ) {}

    /**
     * GET /api/vehicles
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $vehicles = $request->user()
            ->vehicles()
            ->with(['serviceRecords' => fn($q) => $q->latest()->limit(3)])
            ->get();

        return VehicleResource::collection($vehicles);
    }

    /**
     * POST /api/vehicles
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vin' => ['nullable', 'string', 'size:17', 'unique:vehicles,vin'],
            'make' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'current_plate' => ['nullable', 'string', 'max:20'],
            'current_mileage' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:30'],
            'fuel_type' => ['nullable', 'in:gasoline,diesel,electric,hybrid'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Fleet manager limit check
        if ($request->user()->isFleetManager()) {
            $vehicleCount = $request->user()->vehicles()->count();
            if ($vehicleCount >= 10) {
                return response()->json([
                    'message' => 'Fleet managers can manage up to 10 vehicles.',
                ], 422);
            }
        }

        $vehicle = Vehicle::create($validated);

        $request->user()->vehicles()->attach($vehicle->id, [
            'relationship' => 'owner',
            'is_primary' => true,
        ]);

        return response()->json(new VehicleResource($vehicle), 201);
    }

    /**
     * GET /api/vehicles/{vehicle}
     */
    public function show(Request $request, Vehicle $vehicle): VehicleResource
    {
        $this->authorize('view', $vehicle);

        $vehicle->load(['serviceRecords.shop', 'activeReminders']);

        return new VehicleResource($vehicle);
    }

    /**
     * PUT /api/vehicles/{vehicle}
     */
    public function update(Request $request, Vehicle $vehicle): VehicleResource
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'vin' => ['nullable', 'string', 'size:17', 'unique:vehicles,vin,' . $vehicle->id],
            'make' => ['sometimes', 'string', 'max:50'],
            'model' => ['sometimes', 'string', 'max:50'],
            'year' => ['sometimes', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'current_plate' => ['nullable', 'string', 'max:20'],
            'current_mileage' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:30'],
            'fuel_type' => ['nullable', 'in:gasoline,diesel,electric,hybrid'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $vehicle->update($validated);

        return new VehicleResource($vehicle);
    }

    /**
     * DELETE /api/vehicles/{vehicle}
     */
    public function destroy(Request $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return response()->json(['message' => 'Vehicle deleted successfully']);
    }

    /**
     * POST /api/vehicles/decode-vin
     */
    public function decodeVin(Request $request): JsonResponse
    {
        $request->validate([
            'vin' => ['required', 'string', 'size:17'],
        ]);

        $result = $this->vinDecoder->decode($request->vin);

        if ($result['success']) {
            return response()->json($result['data']);
        }

        return response()->json([
            'message' => 'Unable to decode VIN. Please enter vehicle details manually.',
        ], 422);
    }
}
```

---

### ServiceRecordController
**File**: `backend/app/Http/Controllers/Api/ServiceRecordController.php` (new)

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRecordResource;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class ServiceRecordController extends Controller
{
    /**
     * GET /api/vehicles/{vehicle}/services
     */
    public function index(Request $request, Vehicle $vehicle): AnonymousResourceCollection
    {
        $this->authorize('view', $vehicle);

        $records = $vehicle->serviceRecords()
            ->with('shop')
            ->when($request->type, fn($q, $type) => $q->where('service_type', $type))
            ->when($request->from, fn($q, $from) => $q->where('service_date', '>=', $from))
            ->when($request->to, fn($q, $to) => $q->where('service_date', '<=', $to))
            ->orderByDesc('service_date')
            ->paginate(15);

        return ServiceRecordResource::collection($records);
    }

    /**
     * POST /api/vehicles/{vehicle}/services
     */
    public function store(Request $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'service_date' => ['required', 'date', 'before_or_equal:today'],
            'mileage' => ['required', 'integer', 'min:0'],
            'service_type' => ['required', 'in:' . implode(',', array_keys(ServiceRecord::SERVICE_TYPES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'shop_id' => ['nullable', 'exists:service_shops,id'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $record = ServiceRecord::create([
            'vehicle_id' => $vehicle->id,
            'created_by' => $request->user()->id,
            'service_date' => $validated['service_date'],
            'mileage' => $validated['mileage'],
            'service_type' => $validated['service_type'],
            'description' => $validated['description'] ?? null,
            'cost' => $validated['cost'] ?? null,
            'shop_id' => $validated['shop_id'] ?? null,
            'receipt_path' => $receiptPath,
        ]);

        // Update vehicle mileage if higher
        $vehicle->updateMileage($validated['mileage']);

        return response()->json(new ServiceRecordResource($record), 201);
    }

    /**
     * GET /api/services/{record}
     */
    public function show(ServiceRecord $record): ServiceRecordResource
    {
        $this->authorize('view', $record);

        return new ServiceRecordResource($record->load('shop', 'vehicle'));
    }

    /**
     * PUT /api/services/{record}
     */
    public function update(Request $request, ServiceRecord $record): ServiceRecordResource
    {
        $this->authorize('update', $record);

        $validated = $request->validate([
            'service_date' => ['sometimes', 'date', 'before_or_equal:today'],
            'mileage' => ['sometimes', 'integer', 'min:0'],
            'service_type' => ['sometimes', 'in:' . implode(',', array_keys(ServiceRecord::SERVICE_TYPES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'shop_id' => ['nullable', 'exists:service_shops,id'],
        ]);

        $record->update($validated);

        return new ServiceRecordResource($record);
    }

    /**
     * DELETE /api/services/{record}
     */
    public function destroy(ServiceRecord $record): JsonResponse
    {
        $this->authorize('delete', $record);

        if ($record->receipt_path) {
            Storage::disk('public')->delete($record->receipt_path);
        }

        $record->delete();

        return response()->json(['message' => 'Service record deleted successfully']);
    }
}
```

---

## API Routes
**File**: `backend/routes/api.php` (modify)

```php
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\ServiceRecordController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Vehicles
    Route::apiResource('vehicles', VehicleController::class);
    Route::post('/vehicles/decode-vin', [VehicleController::class, 'decodeVin']);

    // Service Records (nested under vehicles)
    Route::get('/vehicles/{vehicle}/services', [ServiceRecordController::class, 'index']);
    Route::post('/vehicles/{vehicle}/services', [ServiceRecordController::class, 'store']);
    Route::get('/services/{record}', [ServiceRecordController::class, 'show']);
    Route::put('/services/{record}', [ServiceRecordController::class, 'update']);
    Route::delete('/services/{record}', [ServiceRecordController::class, 'destroy']);

    // Reminders (nested under vehicles)
    Route::get('/vehicles/{vehicle}/reminders', [ReminderController::class, 'index']);
    Route::post('/vehicles/{vehicle}/reminders', [ReminderController::class, 'store']);
    Route::put('/reminders/{reminder}', [ReminderController::class, 'update']);
    Route::post('/reminders/{reminder}/complete', [ReminderController::class, 'complete']);
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy']);

    // Service Shops
    Route::get('/shops', [ShopController::class, 'index']);
    Route::get('/shops/{shop}', [ShopController::class, 'show']);
    Route::post('/shops/{shop}/favorite', [ShopController::class, 'addFavorite']);
    Route::delete('/shops/{shop}/favorite', [ShopController::class, 'removeFavorite']);
});
```

---

## Services

### VinDecoderService
**File**: `backend/app/Services/VinDecoderService.php` (new)

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VinDecoderService
{
    private const NHTSA_API_URL = 'https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVin';

    /**
     * Decode a VIN using the NHTSA API
     *
     * @param string $vin 17-character VIN
     * @return array{success: bool, data?: array, error?: string}
     */
    public function decode(string $vin): array
    {
        try {
            $response = Http::timeout(10)
                ->get(self::NHTSA_API_URL . "/{$vin}", [
                    'format' => 'json',
                ]);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'error' => 'NHTSA API request failed',
                ];
            }

            $data = $response->json();
            $results = collect($data['Results'] ?? []);

            $make = $this->extractValue($results, 'Make');
            $model = $this->extractValue($results, 'Model');
            $year = $this->extractValue($results, 'Model Year');

            if (!$make || !$model || !$year) {
                return [
                    'success' => false,
                    'error' => 'Unable to decode VIN - missing required fields',
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'vin' => $vin,
                    'make' => $make,
                    'model' => $model,
                    'year' => (int) $year,
                    'fuel_type' => $this->mapFuelType($this->extractValue($results, 'Fuel Type - Primary')),
                    'body_class' => $this->extractValue($results, 'Body Class'),
                    'drive_type' => $this->extractValue($results, 'Drive Type'),
                    'engine' => $this->extractValue($results, 'Engine Model'),
                ],
            ];
        } catch (\Exception $e) {
            Log::error('VIN decode error', ['vin' => $vin, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => 'An error occurred while decoding the VIN',
            ];
        }
    }

    private function extractValue($results, string $variable): ?string
    {
        $item = $results->firstWhere('Variable', $variable);
        $value = $item['Value'] ?? null;
        
        return $value && $value !== 'Not Applicable' ? $value : null;
    }

    private function mapFuelType(?string $nhtsaFuelType): ?string
    {
        if (!$nhtsaFuelType) {
            return null;
        }

        return match (strtolower($nhtsaFuelType)) {
            'gasoline' => 'gasoline',
            'diesel' => 'diesel',
            'electric' => 'electric',
            'hybrid', 'plug-in hybrid' => 'hybrid',
            default => null,
        };
    }
}
```

---

## Livewire Components (Web UI)

### Layout
**File**: `backend/resources/views/components/layouts/app.blade.php` (new)

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MaxFix' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-blue-600">MaxFix</a>
                </div>
                @auth
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('vehicles.index') }}" class="text-gray-700 hover:text-blue-600">My Vehicles</a>
                        <a href="{{ route('shops.index') }}" class="text-gray-700 hover:text-blue-600">Find Shops</a>
                        <livewire:auth.logout />
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
```

---

### Login Component
**File**: `backend/app/Livewire/Auth/Login.php` (new)

```php
<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Login - MaxFix')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return $this->redirect(route('vehicles.index'), navigate: true);
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
```

**File**: `backend/resources/views/livewire/auth/login.blade.php` (new)

```blade
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Login to MaxFix</h2>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    id="email"
                    wire:model="email" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="you@example.com"
                >
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input 
                    type="password" 
                    id="password"
                    wire:model="password" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" wire:model="remember" class="rounded border-gray-300 text-blue-600">
                <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
            >
                <span wire:loading.remove>Login</span>
                <span wire:loading>Logging in...</span>
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline" wire:navigate>Sign up</a>
        </p>
    </div>
</div>
```

---

### Register Component
**File**: `backend/app/Livewire/Auth/Register.php` (new)

```php
<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Register - MaxFix')]
class Register extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    #[Validate('required|in:car_owner,fleet_manager')]
    public string $role = 'car_owner';

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        Auth::login($user);
        session()->regenerate();

        return $this->redirect(route('vehicles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
```

**File**: `backend/resources/views/livewire/auth/register.blade.php` (new)

```blade
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Create Your Account</h2>

        <form wire:submit="register" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    id="email"
                    wire:model="email" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input 
                    type="password" 
                    id="password"
                    wire:model="password" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation"
                    wire:model="password_confirmation" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">I am a...</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" wire:model="role" value="car_owner" class="text-blue-600">
                        <span class="ml-2">Car Owner (personal use)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model="role" value="fleet_manager" class="text-blue-600">
                        <span class="ml-2">Fleet Manager (up to 10 vehicles)</span>
                    </label>
                </div>
                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Create Account</span>
                <span wire:loading>Creating...</span>
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline" wire:navigate>Login</a>
        </p>
    </div>
</div>
```

---

### VehicleList Component
**File**: `backend/app/Livewire/Vehicles/VehicleList.php` (new)

```php
<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('My Vehicles - MaxFix')]
class VehicleList extends Component
{
    public function delete(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);
        $vehicle->delete();
        session()->flash('message', 'Vehicle deleted successfully.');
    }

    public function render()
    {
        $vehicles = Auth::user()
            ->vehicles()
            ->withCount('serviceRecords')
            ->with('activeReminders')
            ->get();

        return view('livewire.vehicles.vehicle-list', [
            'vehicles' => $vehicles,
        ]);
    }
}
```

**File**: `backend/resources/views/livewire/vehicles/vehicle-list.blade.php` (new)

```blade
<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Vehicles</h1>
        <a 
            href="{{ route('vehicles.create') }}" 
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            wire:navigate
        >
            + Add Vehicle
        </a>
    </div>

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if($vehicles->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No vehicles yet</h3>
            <p class="mt-1 text-gray-500">Get started by adding your first vehicle.</p>
            <a href="{{ route('vehicles.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg">
                Add Your First Vehicle
            </a>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($vehicles as $vehicle)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $vehicle->display_name }}
                                </h3>
                                @if($vehicle->current_plate)
                                    <p class="text-sm text-gray-500">{{ $vehicle->current_plate }}</p>
                                @endif
                            </div>
                            @if($vehicle->activeReminders->where('isDue')->count() > 0)
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                    Maintenance Due
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 space-y-2 text-sm text-gray-600">
                            <p>Mileage: {{ number_format($vehicle->current_mileage) }} km</p>
                            <p>Service Records: {{ $vehicle->service_records_count }}</p>
                        </div>

                        <div class="mt-4 flex space-x-2">
                            <a 
                                href="{{ route('vehicles.show', $vehicle) }}" 
                                class="flex-1 text-center bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200"
                                wire:navigate
                            >
                                View
                            </a>
                            <a 
                                href="{{ route('vehicles.edit', $vehicle) }}" 
                                class="flex-1 text-center bg-blue-100 text-blue-700 px-3 py-2 rounded hover:bg-blue-200"
                                wire:navigate
                            >
                                Edit
                            </a>
                            <button 
                                wire:click="delete({{ $vehicle->id }})"
                                wire:confirm="Are you sure you want to delete this vehicle?"
                                class="px-3 py-2 text-red-600 hover:bg-red-100 rounded"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
```

---

## Web Routes
**File**: `backend/routes/web.php` (modify)

```php
<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Vehicles\VehicleList;
use App\Livewire\Vehicles\VehicleForm;
use App\Livewire\Vehicles\VehicleShow;
use App\Livewire\Services\ServiceHistory;
use App\Livewire\Services\ServiceForm;
use App\Livewire\Reminders\ReminderList;
use App\Livewire\Shops\ShopSearch;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Protected
Route::middleware('auth')->group(function () {
    // Vehicles
    Route::get('/vehicles', VehicleList::class)->name('vehicles.index');
    Route::get('/vehicles/create', VehicleForm::class)->name('vehicles.create');
    Route::get('/vehicles/{vehicle}', VehicleShow::class)->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', VehicleForm::class)->name('vehicles.edit');

    // Services
    Route::get('/vehicles/{vehicle}/services', ServiceHistory::class)->name('services.index');
    Route::get('/vehicles/{vehicle}/services/create', ServiceForm::class)->name('services.create');

    // Reminders
    Route::get('/vehicles/{vehicle}/reminders', ReminderList::class)->name('reminders.index');

    // Shops
    Route::get('/shops', ShopSearch::class)->name('shops.index');
});
```

---

## Acceptance Criteria

### Phase 1: Foundation
- [ ] `docker-compose up` starts all services without errors
- [ ] `php artisan migrate` runs all migrations successfully
- [ ] SQLite database file is created at `backend/database/database.sqlite`

### Phase 2: Authentication
- [ ] User can register with name, email, password, and role selection
- [ ] User can login with email and password
- [ ] User is redirected to vehicles page after login
- [ ] API returns JWT token on `/api/login`

### Phase 3: Vehicles
- [ ] User can add vehicle manually (make, model, year, plate)
- [ ] User can add vehicle via VIN (auto-fills make/model/year)
- [ ] User can view list of their vehicles
- [ ] User can edit vehicle details
- [ ] User can delete vehicle (soft delete)
- [ ] Fleet manager cannot add more than 10 vehicles

### Phase 4: Service History
- [ ] User can log a service with date, mileage, type, cost
- [ ] User can upload a receipt image (JPG, PNG, PDF up to 5MB)
- [ ] User can view service timeline for a vehicle
- [ ] User can filter services by type, date range
- [ ] Vehicle mileage auto-updates when service has higher mileage

### Phase 5: Reminders
- [ ] User can create reminder with mileage trigger (e.g., every 5000 km)
- [ ] User can create reminder with date trigger (e.g., every 6 months)
- [ ] System sends email when reminder is due
- [ ] User can mark reminder as complete (updates next due date)
- [ ] User can disable/delete reminder

### Phase 6: Shops
- [ ] User can search shops by city
- [ ] User can filter shops by service type
- [ ] User can add shop to favorites
- [ ] User can remove shop from favorites
- [ ] Shops display distance from user (if geolocation enabled)
