<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parish_id' => $this->parish_id,
            'name' => $this->name,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
