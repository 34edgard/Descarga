<?php

namespace App\Repositories;

use App\Models\Classroom;

class ClassroomRepository extends BaseRepository
{
    /**
     * ClassroomRepository constructor.
     *
     * @param Classroom $classroom
     */
    public function __construct(Classroom $classroom)
    {
        parent::__construct($classroom);
    }
}
