<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'shop' => new ServiceShopResource($this->whenLoaded('shop')),
            'service_date' => $this->service_date,
            'mileage' => $this->mileage,
            'service_type' => $this->service_type,
            'service_type_label' => $this->service_type_label,
            'description' => $this->description,
            'cost' => $this->cost,
            'has_receipt' => $this->hasReceipt(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
