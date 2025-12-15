<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parish extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'municipality_id',
        'name'
    ];

    // Relations
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }
}
