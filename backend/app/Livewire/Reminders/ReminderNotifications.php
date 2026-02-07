<?php

namespace App\Livewire\Reminders;

use App\Models\MaintenanceReminder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReminderNotifications extends Component
{
    public array $notifications = [];
    public int $unreadCount = 0;
    public bool $showDropdown = false;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $vehicles = $user->vehicles->pluck('id');
        
        $this->notifications = MaintenanceReminder::whereIn('vehicle_id', $vehicles)
            ->active()
            ->where('is_active', true)
            ->get()
            ->map(function ($reminder) {
                return [
                    'id' => $reminder->id,
                    'vehicle_name' => $reminder->vehicle->display_name,
                    'service_name' => $reminder->service_name,
                    'due_date' => $reminder->next_due_date?->format('M d, Y'),
                    'due_mileage' => $reminder->next_due_mileage,
                    'is_due' => $reminder->isDue(),
                ];
            })
            ->toArray();

        $this->unreadCount = collect($this->notifications)->filter(fn($n) => $n['is_due'])->count();
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead(int $reminderId): void
    {
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.reminders.reminder-notifications');
    }
}
