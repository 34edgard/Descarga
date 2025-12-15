<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20);
            $table->enum('type', ['landline', 'mobile']);
            $table->string('area_code', 10)->nullable();
            $table->string('carrier', 20)->nullable();
            $table->string('phoneable_type');
            $table->unsignedBigInteger('phoneable_id');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['phoneable_type', 'phoneable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('phones');
    }
};
