<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'school_year_id',
        'section_id',
        'classroom_id',

        'age',
        'shirt_size',
        'pants_size',
        'shoe_size',
        'brachial_circumference',
        'observations',
    ];


    protected static function booted()
    {
        static::creating(function ($enrollment) {
            if (empty($enrollment->age) && $enrollment->student) {
                $birthdate = $enrollment->student->birthdate ?? null;
                if ($birthdate) {
                    $enrollment->age = \Carbon\Carbon::parse($birthdate)->age;
                }
            }
        });
    }


    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function documentsType()
    {
        return $this->belongsToMany(DocumentType::class, 'enrollment_checked_document_types', 'student_id', 'document_type_id')
            ->withTimestamps();
    }
}
