<?php

namespace App\Repositories;

use App\Models\Document;

class DocumentRepository extends BaseRepository
{
    /**
     * DocumentRepository constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document);
    }
}
