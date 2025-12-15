<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'country_id',
        'name'
    ];

    // Relations
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }
}
