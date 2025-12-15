<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disability extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    // Relations
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_disability')
            ->withTimestamps();
    }
}
