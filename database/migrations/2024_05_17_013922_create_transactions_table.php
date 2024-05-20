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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->enum('status', ['RESERVED', 'CONFIRMED', 'CHECKED-IN', 'CHECKED-OUT']);
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->date('check_in_date')->nullable();
            $table->time('check_in_time')->nullable();
            $table->date('check_out_date')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('number_of_guest');
            $table->unsignedBigInteger('guest_id');
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_history_id')->nullable();
            $table->foreign('transaction_history_id')->references('id')->on('transaction_histories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
