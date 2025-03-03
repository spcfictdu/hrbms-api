<?php

namespace App\Http\Controllers;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

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


        // Capture the output of the Artisan commands
        $migrateOutput = Artisan::output();
        Artisan::call('migrate:fresh');
        $migrateOutput .= Artisan::output();

        $seedOutput = Artisan::output();
        Artisan::call('db:seed');
        $seedOutput .= Artisan::output();

        // return response()->json([
        //     'message' => 'Data reset successfully.',
        //     'migrateOutput' => $migrateOutput,
        //     'seedOutput' => $seedOutput
        // ]);
        return $this->success('Data reset successfully.', [
            // 'migrateOutput' => $migrateOutput,
            // 'seedOutput' => $seedOutput
        ]);
    }
}
