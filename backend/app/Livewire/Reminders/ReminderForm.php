<?php

namespace App\Livewire\Reminders;

use App\Models\MaintenanceReminder;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Reminder Form - MaxFix')]
class ReminderForm extends Component
{
    public ?Vehicle $vehicle = null;

    public ?MaintenanceReminder $reminder = null;

    public string $service_name = '';

    public string $reminder_type = 'time';

    public ?int $trigger_mileage = null;

    public ?int $trigger_days = null;

    public ?string $next_due_date = null;

    public ?int $next_due_mileage = null;

    public array $notification_methods = ['email'];

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'service_name' => ['required', 'string', 'max:100'],
            'reminder_type' => ['required', 'in:time,mileage,both'],
            'trigger_mileage' => ['nullable', 'integer', 'min:1'],
            'trigger_days' => ['nullable', 'integer', 'min:1'],
            'next_due_date' => ['nullable', 'date'],
            'next_due_mileage' => ['nullable', 'integer', 'min:0'],
            'notification_methods' => ['required', 'array'],
            'notification_methods.*' => ['in:email,push'],
            'is_active' => ['boolean'],
        ];
    }

    public function mount(Vehicle $vehicle, ?MaintenanceReminder $reminder = null)
    {
        $this->vehicle = $vehicle;
        $this->reminder = $reminder;

        $this->authorize('view', $vehicle);

        if ($reminder) {
            $this->authorize('update', $reminder);
            $this->service_name = $reminder->service_name;
            $this->reminder_type = $reminder->reminder_type;
            $this->trigger_mileage = $reminder->trigger_mileage;
            $this->trigger_days = $reminder->trigger_days;
            $this->next_due_date = $reminder->next_due_date?->format('Y-m-d');
            $this->next_due_mileage = $reminder->next_due_mileage;
            $this->notification_methods = $reminder->notification_methods ?? ['email'];
            $this->is_active = $reminder->is_active;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'service_name' => $this->service_name,
            'reminder_type' => $this->reminder_type,
            'trigger_mileage' => $this->trigger_mileage,
            'trigger_days' => $this->trigger_days,
            'next_due_date' => $this->next_due_date,
            'next_due_mileage' => $this->next_due_mileage,
            'notification_methods' => $this->notification_methods,
            'is_active' => $this->is_active,
        ];

        if ($this->reminder) {
            $this->reminder->update($data);
            session()->flash('message', 'Reminder updated successfully!');
        } else {
            $this->vehicle->reminders()->create([
                ...$data,
                'created_by' => Auth::id(),
            ]);
            session()->flash('message', 'Reminder created successfully!');
        }

        return $this->redirect(route('vehicles.show', $this->vehicle), navigate: true);
    }

    public function render()
    {
        return view('livewire.reminders.reminder-form');
    }
}
