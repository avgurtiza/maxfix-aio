<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceReminderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_name' => $this->service_name,
            'reminder_type' => $this->reminder_type,
            'next_due_date' => $this->next_due_date,
            'next_due_mileage' => $this->next_due_mileage,
            'is_active' => $this->is_active,
            'is_due' => $this->isDue(),
            'created_at' => $this->created_at,
        ];
    }
}
