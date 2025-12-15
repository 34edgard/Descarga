<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepresentativeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_number' => $this->id_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'children_under_6_count' => $this->children_under_6_count,
            'nationality_id' => $this->nationality_id,
            'education_level_id' => $this->education_level_id,
            'occupation_id' => $this->occupation_id,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
