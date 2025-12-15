<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository extends BaseRepository
{
    /**
     * BankRepository constructor.
     *
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        parent::__construct($address);
    }
}
