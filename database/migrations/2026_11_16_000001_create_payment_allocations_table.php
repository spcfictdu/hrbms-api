<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records which part of a transaction each payment paid for.
     *
     * Rows are append-only. `amount` is signed:
     *  - ALLOCATION: positive, money applied to the room / an addon
     *  - RELEASE:    negative, allocation undone but the money stays with the guest
     *                as credit (voided addon, discount applied after payment)
     *  - REFUND:     negative, allocation undone and the money handed back
     */
    public function up(): void
    {
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->unsignedBigInteger('booking_addon_id')->nullable();
            $table->foreign('booking_addon_id')->references('id')->on('booking_addons')->onDelete('cascade');
            $table->enum('item', ['ROOM', 'ADDON']);
            $table->enum('type', ['ALLOCATION', 'RELEASE', 'REFUND'])->default('ALLOCATION');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->index(['transaction_id', 'booking_addon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
