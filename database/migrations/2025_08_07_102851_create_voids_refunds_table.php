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
        Schema::create('voids_refunds', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['VOID', 'REFUND']);
            $table->enum('item', ['ROOM', 'ADDON']);
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->unsignedBigInteger('addon_id')->nullable();
            $table->foreign('addon_id')->references('id')->on('booking_addons');
            $table->unsignedBigInteger('cashier_session_id');
            $table->foreign('cashier_session_id')->references('id')->on('cashier_sessions');
            $table->decimal('amount', 9, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voids_refunds');
    }
};
