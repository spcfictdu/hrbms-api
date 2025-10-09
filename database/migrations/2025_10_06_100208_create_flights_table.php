<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Transaction\Transaction;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->integer('flight_group');
            $table->string('flight_number');
            $table->string('guest_name');
            $table->foreignIdFor(Transaction::class)->constrained()->cascadeOnDelete();
            $table->date('departure_date')->nullable();
            $table->time('departure_time')->nullable();
            $table->date('arrival_date')->nullable();
            $table->time('arrival_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
