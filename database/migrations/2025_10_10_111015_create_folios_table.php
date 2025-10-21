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
        Schema::create('folios', function (Blueprint $table) {
            $table->id();
            $table->enum('item', ['ROOM', 'ADDON'])->default('ROOM');
            $table->foreignIdFor(Transaction::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('booking_addon_id')->nullable();
            $table->foreign('booking_addon_id')->references('id')->on('booking_addons')->onDelete('cascade');
            $table->enum('type', ['INDIVIDUAL', 'SPONSORED'])->default('INDIVIDUAL');
            $table->string('folio_a_name');
            $table->decimal('folio_a_charge', 5, 4)->default(1.00);
            $table->decimal('folio_a_amount', 8, 2);
            $table->string('folio_b_name')->nullable();
            $table->decimal('folio_b_charge', 5, 4)->default(0);
            $table->decimal('folio_b_amount', 8, 2)->default(0);
            $table->string('folio_c_name')->nullable();
            $table->decimal('folio_c_charge', 5, 4)->default(0);
            $table->decimal('folio_c_amount', 8, 2)->default(0);
            $table->string('folio_d_name')->nullable();
            $table->decimal('folio_d_charge', 5, 4)->default(0);
            $table->decimal('folio_d_amount', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folio');
    }
};
