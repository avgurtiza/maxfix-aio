<?php

namespace App\Policies;

use App\Models\ServiceRecord;
use App\Models\User;

class ServiceRecordPolicy
{
    public function view(User $user, ServiceRecord $serviceRecord): bool
    {
        return $user->canManageVehicle($serviceRecord->vehicle);
    }

    public function update(User $user, ServiceRecord $serviceRecord): bool
    {
        return $user->canManageVehicle($serviceRecord->vehicle);
    }

    public function delete(User $user, ServiceRecord $serviceRecord): bool
    {
        return $user->canManageVehicle($serviceRecord->vehicle);
    }
}
