<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'vin' => $this->vin,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'current_plate' => $this->current_plate,
            'current_mileage' => $this->current_mileage,
            'color' => $this->color,
            'fuel_type' => $this->fuel_type,
            'notes' => $this->notes,
            'display_name' => $this->display_name,
            'service_records' => ServiceRecordResource::collection($this->whenLoaded('serviceRecords')),
            'reminders' => MaintenanceReminderResource::collection($this->whenLoaded('activeReminders')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
