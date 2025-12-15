<?php

namespace App\Repositories;

use App\Models\Relationship;

class RelationshipRepository extends BaseRepository
{
    /**
     * RelationshipRepository constructor.
     *
     * @param Relationship $relationship
     */
    public function __construct(Relationship $relationship)
    {
        parent::__construct($relationship);
    }
}
