<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_number', // Cédula de identidad
        'first_name',
        'last_name',
        'birth_date',
        'gender',

        'education_level_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relations
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function teacherClassrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_teachers');
    }
}
