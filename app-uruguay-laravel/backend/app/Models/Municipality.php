<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Municipality extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'state_id',
        'name'
    ];

    // Relations
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function parishes()
    {
        return $this->hasMany(Parish::class);
    }
}
