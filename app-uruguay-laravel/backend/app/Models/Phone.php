<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'type',
        'area_code',
        'carrier',
        'phoneable_type',
        'phoneable_id'
    ];

    // Relations
    public function phoneable()
    {
        return $this->morphTo();
    }
}
