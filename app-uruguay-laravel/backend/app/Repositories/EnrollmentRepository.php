<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository extends BaseRepository
{
    /**
     * EnrollmentRepository constructor.
     *
     * @param Enrollment $enrollment
     */
    public function __construct(Enrollment $enrollment)
    {
        parent::__construct($enrollment);
    }
}
