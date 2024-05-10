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
        Schema::create('room_type_rates', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->unsignedBigInteger('room_type_id');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('cascade');
            $table->enum('type', ['REGULAR', 'SPECIAL']);
            $table->string('discount_name')->nullable();
            $table->date('start_date')->format('m/d/Y')->nullable();
            $table->date('end_date')->format('m/d/Y')->nullable();
            $table->float('monday');
            $table->float('tuesday');
            $table->float('wednesday');
            $table->float('thursday');
            $table->float('friday');
            $table->float('saturday');
            $table->float('sunday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_rates');
    }
};
