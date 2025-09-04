<?php

use App\Models\Transaction\Transaction;
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
        Schema::create('booking_addons', function (Blueprint $table) {
            $table->id();
            // $table->foreignIdFor(Transaction::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_batch')->default(1);
            $table->enum('payment_status', ['PENDING', 'PAID', 'VOIDED', 'REFUNDED'])->default('PENDING');
            $table->string('name');
            // $table->unsignedBigInteger('addon_id');
            // $table->foreign('addon_id')->references('id')->on('add_ons')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_addons');
    }
};
