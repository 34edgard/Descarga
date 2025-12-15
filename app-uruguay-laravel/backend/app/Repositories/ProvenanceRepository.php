<?php

namespace App\Repositories;

use App\Models\Provenance;

class ProvenanceRepository extends BaseRepository
{
    /**
     * ProvenanceRepository constructor.
     *
     * @param Provenance $provenance
     */
    public function __construct(Provenance $provenance)
    {
        parent::__construct($provenance);
    }
}
