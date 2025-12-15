<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Representative extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nationality_id',
        'education_level_id',
        'occupation_id',

        'id_number',
        'first_name',
        'last_name',
        'birth_date',
        'children_under_6_years_count', // default 0
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    //Relations
    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'representative_student')
            ->withPivot('relationship_id', 'is_primary')
            ->withTimestamps();
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
