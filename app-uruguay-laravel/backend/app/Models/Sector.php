<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sector extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parish_id',
        'name'
    ];

    // Relations
    public function parish()
    {
        return $this->belongsTo(Parish::class);
    }
}
