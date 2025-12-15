<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section_id' => $this->section_id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'shift' => $this->shift,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
