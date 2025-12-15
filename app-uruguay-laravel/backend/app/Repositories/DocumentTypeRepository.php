<?php

namespace App\Repositories;

use App\Models\DocumentType;

class DocumentTypeRepository extends BaseRepository
{
    /**
     * DocumentTypeRepository constructor.
     *
     * @param DocumentType $documentType
     */
    public function __construct(DocumentType $documentType)
    {
        parent::__construct($documentType);
    }
}
