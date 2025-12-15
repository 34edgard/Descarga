<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sector_id',
        'house_number',
        'street',
        'addressable_type',
        'addressable_id',
        'type'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function addressable()
    {
        return $this->morphTo();
    }
}
