<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceShopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'services_offered' => $this->services_offered,
            'operating_hours' => $this->operating_hours,
            'is_verified' => $this->is_verified,
            'distance' => $this->when(isset($this->distance), $this->distance),
        ];
    }
}
