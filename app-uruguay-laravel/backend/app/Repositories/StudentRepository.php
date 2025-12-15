<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository extends BaseRepository
{
    /**
     * StudentRepository constructor.
     *
     * @param Student $student
     */
    public function __construct(Student $student)
    {
        parent::__construct($student);
    }
}
