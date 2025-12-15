<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {;
            $table->id();
            $table->foreignId('nationality_id')->constrained('nationalities'); // Nacionalidad del estudiante
            $table->foreignId('provenance_id')->constrained('provenances'); // Hogar, Multihogar, Guardería, Otro plantel
            $table->foreignId('medical_condition_id')->constrained('medical_conditions'); // condiciones médicas
            $table->foreignId('disability_id')->constrained('disabilities'); // Discapacidad
            $table->foreignId('nutritional_status_id')->constrained('nutritional_statuses'); // Condición nutricional

            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->string('previous_school')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['first_name', 'last_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
