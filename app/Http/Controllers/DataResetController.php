<?php

namespace App\Http\Controllers;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class DataResetController extends Controller
{
    use ResponseAPI;

    function resetData()
    {
        // Manually authenticate the user using the Sanctum guard
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!Gate::forUser($user)->allows('admin-only')) {
            return response()->json(['message' => 'Unauthorized, admin only'], 403);
        }

        // Start the timer
        $startTime = microtime(true);

        // Capture the output of the Artisan commands
        $migrateOutput = Artisan::output();
        Artisan::call('migrate:fresh');
        $migrateOutput .= Artisan::output();

        $seedOutput = Artisan::output();
        Artisan::call('db:seed');
        $seedOutput .= Artisan::output();

        $dbSize = $this->getDatabaseSize();

        // End the timer
        $endTime = microtime(true);
        $executionTime = number_format($endTime - $startTime, 2);

        // Count the number of migrations and seeders
        $migrationsCount = count(DB::table('migrations')->get());
        // $seedersCount = count(DB::table('seeders')->get());

        return $this->success('Data reset successfully.', [
            'executionTime' => $executionTime . ' seconds',
            'migrationsCount' => $migrationsCount,
            // 'seedersCount' => $seedersCount,
            'dbSize' => $dbSize . ' MB',
            // 'dbSizeAfter' => $dbSizeAfter . ' MB',
            // 'migrateOutput' => $migrateOutput,
            // 'seedOutput' => $seedOutput
        ]);
    }

    private function getDatabaseSize()
    {
        $databaseName = env('DB_DATABASE');
        $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size FROM information_schema.tables WHERE table_schema = '$databaseName'");
        return $result[0]->size ?? 0;
    }
}
