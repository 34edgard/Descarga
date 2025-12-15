<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    // Relations
    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'enrollment_checked_document_types', 'document_type_id', 'student_id')
            ->withTimestamps();
    }
}
