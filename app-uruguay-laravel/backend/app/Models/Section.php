<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'level_id',
        'name',
    ];

    // Relations
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
