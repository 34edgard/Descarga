<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nationality_id' => $this->nationality_id,
            'provenance_id' => $this->provenance_id,
            'medical_condition_id' => $this->medical_condition_id,
            'disability_id' => $this->disability_id,
            'nutritional_status_id' => $this->nutritional_status_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'previous_school' => $this->previous_school,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
