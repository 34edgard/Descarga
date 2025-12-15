<?php

namespace App\Repositories;

use App\Models\Occupation;

class OccupationRepository extends BaseRepository
{
    /**
     * OccupationRepository constructor.
     *
     * @param Occupation $occupation
     */
    public function __construct(Occupation $occupation)
    {
        parent::__construct($occupation);
    }
}
