<?php

use App\Models\Discount\Voucher;
use App\Models\Discount\Discount;
use Illuminate\Support\Facades\Schema;
use App\Models\Discount\PaymentDiscount;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->enum('payment_type', ['CASH', 'GCASH','CHEQUE', 'CREDIT_CARD']);
            // $table->foreignIdFor(PaymentDiscount::class)->nullable()->constrained();
            // $table->foreignIdFor(Voucher::class)->nullable()->constrained();
            $table->mediumInteger('amount_received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
