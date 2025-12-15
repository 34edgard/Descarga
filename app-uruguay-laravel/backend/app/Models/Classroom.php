<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'name',
        'capacity',
        'shift'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'shift' => 'string',
    ];

    // Relations
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function classroomTeachers()
    {
        return $this->belongsToMany(Teacher::class, 'classroom_teachers');
    }
}
