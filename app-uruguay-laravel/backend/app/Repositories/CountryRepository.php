<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository extends BaseRepository
{
    /**
     * CountryRepository constructor.
     *
     * @param Country $country
     */
    public function __construct(Country $country)
    {
        parent::__construct($country);
    }
}
