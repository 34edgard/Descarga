<?php

namespace App\Repositories;

use App\Models\EducationLevel;

class EducationLevelRepository extends BaseRepository
{
    /**
     * EducationLevelRepository constructor.
     *
     * @param EducationLevel $educationLevel
     */
    public function __construct(EducationLevel $educationLevel)
    {
        parent::__construct($educationLevel);
    }
}
