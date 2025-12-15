<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'type' => $this->type,
            'area_code' => $this->area_code,
            'carrier' => $this->carrier,
            'phoneable_type' => $this->phoneable_type,
            'phoneable_id' => $this->phoneable_id,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
