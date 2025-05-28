<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class GenerateGuestDemographicsReportRepository
{
    public function execute(Request $request)
    {
        if (!Auth::user()->hasRole('ADMIN') && !Auth::user()->hasRole('FRONT DESK')) {
            return [
                'message' => 'Unauthorized access.',
                'data' => []
            ];
        }

        $startDate = $request->query('start');
        $endDate = $request->query('end');

        if (!$startDate || !$endDate) {
            return [
                'error' => 'Missing start or end date',
                'status' => 400
            ];
        }

        $transactions = Transaction::with('guest')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $provinces = collect();
        $cities = collect();

        foreach ($transactions as $transaction) {
            $provinces->push($transaction->guest->province);
            $cities->push($transaction->guest->city);
        }

        $byProvince = $provinces->countBy()->toArray();
        $byCity = $cities->countBy()->toArray();

        return [
            'byProvince' => $byProvince,
            'byCity' => $byCity,
        ];
    }
}