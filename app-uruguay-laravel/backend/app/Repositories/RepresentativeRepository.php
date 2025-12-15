<?php

namespace App\Repositories;

use App\Models\Representative;

class RepresentativeRepository extends BaseRepository
{
    /**
     * RepresentativeRepository constructor.
     *
     * @param Representative $representative
     */
    public function __construct(Representative $representative)
    {
        parent::__construct($representative);
    }
}
