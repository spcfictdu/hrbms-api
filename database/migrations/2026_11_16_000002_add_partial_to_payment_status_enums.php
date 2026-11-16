<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The code already referenced a PARTIAL payment status, but the enums only
     * allowed PENDING / PAID / VOIDED / REFUNDED.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach (['transactions', 'booking_addons'] as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `payment_status` ENUM('PENDING','PARTIAL','PAID','VOIDED','REFUNDED') NOT NULL DEFAULT 'PENDING'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach (['transactions', 'booking_addons'] as $table) {
            DB::table($table)->where('payment_status', 'PARTIAL')->update(['payment_status' => 'PENDING']);
            DB::statement("ALTER TABLE `{$table}` MODIFY `payment_status` ENUM('PENDING','PAID','VOIDED','REFUNDED') NOT NULL DEFAULT 'PENDING'");
        }
    }
};
