<?php

namespace App\Console\Commands;

use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\Payment\PaymentAllocator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillPaymentAllocations extends Command
{
    protected $signature = 'payments:backfill-allocations {--dry-run : Roll everything back at the end}';

    protected $description = 'Create payment allocations for existing transactions that have payments but no allocations';

    public function handle(PaymentAllocator $allocator): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $count = 0;

        DB::beginTransaction();

        Transaction::whereNotIn('payment_status', ['VOIDED', 'REFUNDED'])
            ->whereHas('payment')
            ->whereDoesntHave('allocations')
            ->orderBy('id')
            ->chunkById(100, function ($transactions) use ($allocator, &$count) {
                foreach ($transactions as $transaction) {
                    $allocator->reconcile($transaction);
                    $count++;
                }
            });

        $dryRun ? DB::rollBack() : DB::commit();

        $this->info(($dryRun ? '[dry run] ' : '') . "Reconciled {$count} transaction(s).");

        return self::SUCCESS;
    }
}
