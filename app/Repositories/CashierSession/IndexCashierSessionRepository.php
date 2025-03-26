<?php

namespace App\Repositories\CashierSession;

use App\Models\CashierSession\CashierSession;
use App\Repositories\BaseRepository;
use App\Traits\QueryBuilder\QueryBuilder;

class IndexCashierSessionRepository extends BaseRepository
{
    use QueryBuilder;
    public function execute($request)
    {
        $filterableFields = [
            // 'openingBalance' => 'opening_balance',
            // 'closingBalance' => 'closing_balance',
            'openedAt' => 'opened_at',
            'closedAt' => 'closed_at',
            'status' => 'status'
        ];

        $searchableFields = [
            'opening_balance',
            'closing_balance',
            'opened_at' => [
                'type' => 'date'
            ],
            'closed_at' => [
                'type' => 'date'
            ],
            'status',
            'user.last_name',
            'user.first_name'
        ];

        $sortableFields = [
            'openingBalance' => [
                'column' => 'opening_balance'
            ],
            'closingBalance' => [
                'column' => 'closing_balance'
            ],
            'openedAt' => [
                'column' => 'opened_at'
            ],
            'closedAt' => [
                'column' => 'closed_at'
            ],
            'status' => [
                'column' => 'status'
            ]
        ];


        $query = CashierSession::query();

        $cashierSessions = $this->applyQueryModifications($query, $request, $filterableFields, $searchableFields, $sortableFields, '', 'desc');

        $cashierSessions = $cashierSessions->map(function ($cashierSession) {
            return [
                "openingBalance" => $cashierSession->opening_balance,
                "closingBalance" => $cashierSession->closing_balance,
                "openedAt" => $cashierSession->opened_at,
                "closedAt" => $cashierSession->closed_at,
                "status" => $cashierSession->status,
                "userFullName" => $cashierSession->user->full_name
            ];
        });



        return $this->success("Successfully retrieved cashier sessions", $cashierSessions);
    }
}
