<?php

namespace App\Repositories;

use App\Models\NutritionalStatus;

class NutritionalStatusRepository extends BaseRepository
{
    /**
     * NutritionalStatusRepository constructor.
     *
     * @param NutritionalStatus $nutritionalStatus
     */
    public function __construct(NutritionalStatus $nutritionalStatus)
    {
        parent::__construct($nutritionalStatus);
    }
}
