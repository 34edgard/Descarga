<?php

namespace App\Repositories;

use App\Models\Level;

class LevelRepository extends BaseRepository
{
    /**
     * LevelRepository constructor.
     *
     * @param Level $level
     */
    public function __construct(Level $level)
    {
        parent::__construct($level);
    }
}
