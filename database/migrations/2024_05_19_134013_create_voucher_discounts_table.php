<?php

use App\Models\Discount\Voucher;
use App\Models\Discount\Discount;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voucher_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Payment::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Transaction::class)->constrained()->cascadeOnDelete();
            $table->string('discount');
            $table->foreignIdFor(Voucher::class)->constrained()->cascadeOnDelete();
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_discounts');
    }
};
