<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'school_year_id' => $this->school_year_id,
            'section_id' => $this->section_id,
            'classroom_id' => $this->classroom_id,
            'age' => $this->age,
            'shirt_size' => $this->shirt_size,
            'pants_size' => $this->pants_size,
            'shoe_size' => $this->shoe_size,
            'brachial_circumference' => $this->brachial_circumference,
            'observations' => $this->observations,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
