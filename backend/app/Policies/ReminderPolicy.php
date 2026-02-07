<?php

namespace App\Policies;

use App\Models\MaintenanceReminder;
use App\Models\User;

class ReminderPolicy
{
    public function view(User $user, MaintenanceReminder $reminder): bool
    {
        return $user->canManageVehicle($reminder->vehicle);
    }

    public function update(User $user, MaintenanceReminder $reminder): bool
    {
        return $user->canManageVehicle($reminder->vehicle);
    }

    public function delete(User $user, MaintenanceReminder $reminder): bool
    {
        return $user->canManageVehicle($reminder->vehicle);
    }
}
