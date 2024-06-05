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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('bed_size');
            $table->string('property_size');
            $table->boolean('is_non_smoking');
            $table->boolean('balcony_or_terrace');
            $table->integer('capacity');
            $table->integer('extra_person_capacity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
