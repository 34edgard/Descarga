<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained();
            $table->string('house_number', 20);
            $table->string('street', 100);
            $table->string('addressable_type'); // Polymorphic
            $table->unsignedBigInteger('addressable_id'); // Polymorphic
            $table->enum('type', ['home', 'work']);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['addressable_type', 'addressable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
