<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sector_id' => $this->sector_id,
            'house_number' => $this->house_number,
            'street' => $this->street,
            'addressable_type' => $this->addressable_type,
            'addressable_id' => $this->addressable_id,
            'type' => $this->type,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
