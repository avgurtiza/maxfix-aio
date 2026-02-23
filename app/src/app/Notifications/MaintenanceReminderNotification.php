<?php

namespace App\Notifications;

use App\Models\MaintenanceReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private MaintenanceReminder $reminder
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $this->reminder->vehicle;
        $dueInfo = [];

        if ($this->reminder->next_due_date) {
            $dueInfo[] = 'Due date: '.$this->reminder->next_due_date->format('M d, Y');
        }

        if ($this->reminder->next_due_mileage) {
            $dueInfo[] = 'Due mileage: '.number_format($this->reminder->next_due_mileage).' km';
        }

        return (new MailMessage)
            ->subject('Maintenance Reminder: '.$this->reminder->service_name)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('This is a reminder for upcoming vehicle maintenance.')
            ->line('Vehicle: '.$vehicle->display_name)
            ->line('Service: '.$this->reminder->service_name)
            ->lines($dueInfo)
            ->action('View Vehicle', url('/vehicles/'.$vehicle->uuid))
            ->line('Thank you for using MaxFix!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reminder_id' => $this->reminder->id,
            'vehicle_id' => $this->reminder->vehicle_id,
            'service_name' => $this->reminder->service_name,
            'next_due_date' => $this->reminder->next_due_date,
            'next_due_mileage' => $this->reminder->next_due_mileage,
        ];
    }
}
