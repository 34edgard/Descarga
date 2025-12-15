<?php

namespace App\Repositories;

use App\Models\Parish;

class ParishRepository extends BaseRepository
{
    /**
     * ParishRepository constructor.
     *
     * @param Parish $parish
     */
    public function __construct(Parish $parish)
    {
        parent::__construct($parish);
    }
}
