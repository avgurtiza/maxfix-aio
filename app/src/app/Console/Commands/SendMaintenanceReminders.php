<?php

namespace App\Console\Commands;

use App\Models\MaintenanceReminder;
use App\Notifications\MaintenanceReminderNotification;
use Illuminate\Console\Command;

class SendMaintenanceReminders extends Command
{
    protected $signature = 'app:send-maintenance-reminders';

    protected $description = 'Send notifications for due maintenance reminders';

    public function handle(): int
    {
        $this->info('Checking for due maintenance reminders...');

        $dueReminders = MaintenanceReminder::with(['vehicle.users', 'creator'])
            ->active()
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('next_due_date')
                        ->where('next_due_date', '<=', now()->addDays(7))
                        ->where('next_due_date', '>=', now());
                })->orWhere(function ($q) {
                    $q->whereNotNull('next_due_mileage')
                        ->whereHas('vehicle', function ($vehicleQuery) {
                            $vehicleQuery->whereRaw('maintenance_reminders.next_due_mileage <= vehicles.current_mileage + 500');
                        });
                });
            })
            ->where(function ($query) {
                $query->whereNull('last_notified_at')
                    ->orWhere('last_notified_at', '<=', now()->subDay());
            })
            ->get();

        $count = 0;

        foreach ($dueReminders as $reminder) {
            $notificationMethods = $reminder->notification_methods ?? ['email'];

            if (in_array('email', $notificationMethods)) {
                foreach ($reminder->vehicle->users as $user) {
                    $user->notify(new MaintenanceReminderNotification($reminder));
                }
            }

            $reminder->update(['last_notified_at' => now()]);
            $count++;
        }

        $this->info("Sent {$count} maintenance reminder notifications.");

        return self::SUCCESS;
    }
}
