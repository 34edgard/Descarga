<?php

namespace App\Repositories;

use App\Models\Phone;

class PhoneRepository extends BaseRepository
{
    /**
     * PhoneRepository constructor.
     *
     * @param Phone $phone
     */
    public function __construct(Phone $phone)
    {
        parent::__construct($phone);
    }
}
