<?php

namespace App\Repositories;

use App\Models\Sector;

class SectorRepository extends BaseRepository
{
    /**
     * SectorRepository constructor.
     *
     * @param Sector $sector
     */
    public function __construct(Sector $sector)
    {
        parent::__construct($sector);
    }
}
