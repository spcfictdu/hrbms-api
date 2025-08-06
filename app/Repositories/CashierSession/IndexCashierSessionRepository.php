<?php

namespace App\Repositories\CashierSession;

use App\Models\CashierSession\CashierSession;
use App\Repositories\BaseRepository;
use App\Traits\QueryBuilder\QueryBuilder;
use App\Models\Transaction\Payment;

class IndexCashierSessionRepository extends BaseRepository
{
    use QueryBuilder;
    public function execute($request)
    {
        $filterableFields = [
            'openingBalance' => 'opening_balance',
            'closingBalance' => 'closing_balance',
            'beginningBalance' => 'beginning_balance',
            'openingAdjustment' => 'opening_adjustment',
            'openedAt' => 'opened_at',
            'closedAt' => 'closed_at',
            'status' => 'status'
        ];

        $searchableFields = [
            'opening_balance',
            'closing_balance',
            'beginning_balance',
            'opening_adjustment',
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
            'beginningBalance' => [
                'column' => 'beginning_balance'
            ],
            'openingAdjustment' => [
                'column' => 'opening_adjustment'
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

        $allPaymentTypes = ['CASH', 'GCASH', 'CHEQUE', 'CREDIT_CARD'];

        foreach ($cashierSessions as $cashierSession) {
            $payments = $cashierSession->payments->groupBy('payment_type')->map(function ($payments, $types) {
                return [
                    'name' => $types,
                    'totalAmount' => number_format((float) $payments->sum('amount_received'), 2, '.', '')
                ];
            })->values();

            foreach ($allPaymentTypes as $type) {
                if (!$payments->contains('name', $type)) {
                    $payments->push([
                        'name' => $type,
                        'totalAmount' => '0.00'
                    ]);
                }
            }

            $cashierSession->computed_payments = $payments;
        }

        $cashierSessions = $cashierSessions->map(function ($cashierSession) {
            return [
                "cashierSessionId" => $cashierSession->id,
                "openingBalance" => $cashierSession->opening_balance,
                "closingBalance" => $cashierSession->closing_balance,
                "beginningBalance" => $cashierSession->beginning_balance,
                "openingAdjustment" => $cashierSession->opening_adjustment,
                "openedAt" => $cashierSession->opened_at,
                "closedAt" => $cashierSession->closed_at,
                "status" => $cashierSession->status,
                "userFullName" => $cashierSession->user->full_name,
                "userId" => $cashierSession->user->id,
                "payments" => $cashierSession->computed_payments
            ];
        });

        return $this->success("Successfully retrieved cashier sessions", $cashierSessions);
    }
}
