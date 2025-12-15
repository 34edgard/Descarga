<?php

namespace App\Repositories;

use App\Models\SchoolYear;

class SchoolYearRepository extends BaseRepository
{
    /**
     * SchoolYearRepository constructor.
     *
     * @param SchoolYear $schoolYear
     */
    public function __construct(SchoolYear $schoolYear)
    {
        parent::__construct($schoolYear);
    }
}
