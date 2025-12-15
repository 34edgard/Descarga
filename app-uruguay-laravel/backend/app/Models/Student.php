<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'birth_place_id',
        'nationality_id',
        'provenance_id', // Hogar, Multihogar, Guardería, Otro plantel
        'medical_condition_id',
        'disability_id',
        'nutritional_status_id',

        'first_name',
        'last_name',
        'birthdate',
        'gender', // Masculino, Femenino, Otro
        'previous_school', // Nombre de la escuela anterior (opcional)
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];


    // Accessors

    /**
     * Get the age of the student as years and months based on their birth date.
     *
     * @return string|null
     */
    public function getAgeAttribute()
    {
        if (empty($this->birthdate)) {
            return null;
        }

        try {
            $birthDate = $this->birthdate instanceof \Carbon\Carbon
                ? $this->birthdate
                : \Carbon\Carbon::parse($this->birthdate);

            $now = \Carbon\Carbon::now();
            $years = $birthDate->diffInYears($now);
            $months = $birthDate->addYears($years)->diffInMonths($now);

            return "{$years} años, {$months} meses";
        } catch (\Exception $e) {
            return null;
        }
    }




    // Relations
    public function provenance()
    {
        return $this->belongsTo(Provenance::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function birthPlace()
    {
        return $this->belongsTo(Address::class, 'birth_place_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function medicalCondition()
    {
        return $this->belongsTo(MedicalCondition::class);
    }

    public function nutritionalStatus()
    {
        return $this->belongsTo(NutritionalStatus::class);
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable'); // Polymorphic relation for phones
    }

    public function representatives()
    {
        return $this->belongsToMany(Representative::class, 'student_representative') // Many-to-many relationship with pivot table
            ->withPivot('relationship_id', 'is_primary')
            ->withTimestamps();
    }

    public function disability()
    {
        return $this->belongsToMany(Disability::class, 'student_disability') // Many-to-many relationship
            ->withTimestamps();
    }
}
