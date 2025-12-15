<?php

namespace App\Repositories;

use App\Models\Municipality;

class MunicipalityRepository extends BaseRepository
{
    /**
     * MunicipalityRepository constructor.
     *
     * @param Municipality $municipality
     */
    public function __construct(Municipality $municipality)
    {
        parent::__construct($municipality);
    }
}
