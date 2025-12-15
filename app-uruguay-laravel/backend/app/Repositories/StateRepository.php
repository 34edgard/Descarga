<?php

namespace App\Repositories;

use App\Models\State;

class StateRepository extends BaseRepository
{
    /**
     * StateRepository constructor.
     *
     * @param State $state
     */
    public function __construct(State $state)
    {
        parent::__construct($state);
    }
}
