<?php

namespace App\Http\Controllers\ReportGeneration;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\ReportGeneration\{
    GenerateRevenueReportRequest,
    GeneratePaymentReportRequest
};

// * REPOSITORY
use App\Repositories\ReportGeneration\{
    GenerateRevenueReportRepository,
    GeneratePaymentReportRepository
};

class ReportGenerationController extends Controller
{
    protected $revenueReport, $paymentReport;

    public function __construct(
        GenerateRevenueReportRepository $revenueReport,
        GeneratePaymentReportRepository $paymentReport
    ) {
        $this->revenueReport = $revenueReport;
        $this->paymentReport = $paymentReport;
    }

    protected function revenueReport(GenerateRevenueReportRequest $request)
    {
        return $this->revenueReport->execute($request);
    }

    protected function paymentReport(GeneratePaymentReportRequest $request)
    {
        return $this->paymentReport->execute($request);
    }
}
