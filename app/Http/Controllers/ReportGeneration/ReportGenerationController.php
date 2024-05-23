<?php

namespace App\Http\Controllers\ReportGeneration;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\ReportGeneration\{
    GenerateRevenueReportRequest,
    GeneratePaymentReportRequest,
    GenerateCheckInOutReportRequest
};

// * REPOSITORY
use App\Repositories\ReportGeneration\{
    GenerateRevenueReportRepository,
    GeneratePaymentReportRepository,
    GenerateCheckInOutReportRepository
};

class ReportGenerationController extends Controller
{
    protected $revenueReport, $paymentReport, $checkInOutReport;

    public function __construct(
        GenerateRevenueReportRepository $revenueReport,
        GeneratePaymentReportRepository $paymentReport,
        GenerateCheckInOutReportRepository $checkInOutReport
    ) {
        $this->revenueReport = $revenueReport;
        $this->paymentReport = $paymentReport;
        $this->checkInOutReport = $checkInOutReport;
    }

    protected function revenueReport(GenerateRevenueReportRequest $request)
    {
        return $this->revenueReport->execute($request);
    }

    protected function paymentReport(GeneratePaymentReportRequest $request)
    {
        return $this->paymentReport->execute($request);
    }

    protected function checkInOutReport(GenerateCheckInOutReportRequest $request)
    {
        return $this->checkInOutReport->execute($request);
    }
}
