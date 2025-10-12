<?php

use App\Models\Transaction\Payment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\PaymentType\Bank;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_card_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Payment::class)->constrained()->cascadeOnDelete();
            $table->string('card_number'); // best practice to only store last 4 digits
            $table->string('card_holder_name');
            $table->string('expiration_date', 5);
            $table->string('cvc');
            $table->foreignIdFor(Bank::class)->constrained()->cascadeOnDelete();
            // $table->mediumInteger('amount_received'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_card_payments');
    }
};
