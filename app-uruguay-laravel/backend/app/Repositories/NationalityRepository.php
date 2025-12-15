<?php

namespace App\Repositories;

use App\Models\Nationality;

class NationalityRepository extends BaseRepository
{
    /**
     * NationalityRepository constructor.
     *
     * @param Nationality $nationality
     */
    public function __construct(Nationality $nationality)
    {
        parent::__construct($nationality);
    }
}
