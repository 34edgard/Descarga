<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'level_id' => $this->level_id,
            //'level_name' => $this->level ? $this->level->name : null,
            'name' => $this->name,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
