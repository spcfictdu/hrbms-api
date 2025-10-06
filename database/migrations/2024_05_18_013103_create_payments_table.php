<?php

use App\Models\User;
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
            $table->foreignIdFor(User::class)->cascadeOnDelete();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreignId('cashier_session_id');
            $table->enum('payment_type', ['CASH', 'GCASH', 'CHEQUE', 'CREDIT_CARD']);
            // $table->foreignIdFor(PaymentDiscount::class)->nullable()->constrained();
            // $table->foreignIdFor(Voucher::class)->nullable()->constrained();
            $table->decimal('amount_received', 9, 2);
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
