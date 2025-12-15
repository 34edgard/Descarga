<?php

namespace App\Repositories;

use App\Models\Disability;

class DisabilityRepository  extends BaseRepository
{
    /**
     * DisabilityRepository constructor.
     *
     * @param Disability $disability
     */
    public function __construct(Disability $disability)
    {
        parent::__construct($disability);
    }
}
