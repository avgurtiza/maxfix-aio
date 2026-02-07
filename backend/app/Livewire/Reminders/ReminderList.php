<?php

namespace App\Livewire\Reminders;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Maintenance Reminders - MaxFix')]
class ReminderList extends Component
{
    public ?Vehicle $vehicle = null;

    public string $filter = 'all';

    public function delete(int $reminderId)
    {
        $reminder = \App\Models\MaintenanceReminder::findOrFail($reminderId);
        $this->authorize('delete', $reminder);
        $reminder->delete();
        session()->flash('message', 'Reminder deleted successfully.');
    }

    public function complete(int $reminderId)
    {
        $reminder = \App\Models\MaintenanceReminder::findOrFail($reminderId);
        $this->authorize('update', $reminder);

        $currentMileage = $reminder->vehicle->current_mileage;
        $reminder->markCompleted($currentMileage);

        session()->flash('message', 'Reminder marked as complete.');
    }

    public function render()
    {
        $query = Auth::user()->vehicles()
            ->with(['reminders' => function ($query) {
                if ($this->filter === 'active') {
                    $query->where('is_active', true);
                } elseif ($this->filter === 'due') {
                    $query->where('is_active', true)
                        ->where(function ($q) {
                            $q->where(function ($q) {
                                $q->whereNotNull('next_due_date')
                                    ->where('next_due_date', '<=', now());
                            })->orWhere(function ($q) {
                                $q->whereNotNull('next_due_mileage')
                                    ->whereHas('vehicle', function ($vehicleQuery) {
                                        $vehicleQuery->whereRaw('maintenance_reminders.next_due_mileage <= vehicles.current_mileage');
                                    });
                            });
                        });
                }
            }]);

        if ($this->vehicle) {
            $this->authorize('view', $this->vehicle);
            $vehicles = collect([$this->vehicle->load('reminders')]);
        } else {
            $vehicles = $query->get();
        }

        return view('livewire.reminders.reminder-list', [
            'vehicles' => $vehicles,
        ]);
    }
}
