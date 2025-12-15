<?php

namespace App\Repositories;

use App\Models\Section;

class SectionRepository extends BaseRepository
{
    /**
     * SectionRepository constructor.
     *
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        parent::__construct($section);
    }
}
