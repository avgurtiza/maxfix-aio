<?php

namespace App\Providers;

use App\Models\MaintenanceReminder;
use App\Models\ServiceRecord;
use App\Models\ServiceShop;
use App\Models\Vehicle;
use App\Policies\ReminderPolicy;
use App\Policies\ServiceRecordPolicy;
use App\Policies\ShopPolicy;
use App\Policies\VehiclePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Vehicle::class => VehiclePolicy::class,
        ServiceRecord::class => ServiceRecordPolicy::class,
        MaintenanceReminder::class => ReminderPolicy::class,
        ServiceShop::class => ShopPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
