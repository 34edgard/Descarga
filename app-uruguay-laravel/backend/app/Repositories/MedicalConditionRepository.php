<?php

namespace App\Repositories;

use App\Models\MedicalCondition;

class MedicalConditionRepository extends BaseRepository
{
    /**
     * MedicalConditionRepository constructor.
     *
     * @param MedicalCondition $medicalCondition
     */
    public function __construct(MedicalCondition $medicalCondition)
    {
        parent::__construct($medicalCondition);
    }
}
