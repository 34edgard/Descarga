<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    // Relations
    public function representatives()
    {
        return $this->hasMany(Representative::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
