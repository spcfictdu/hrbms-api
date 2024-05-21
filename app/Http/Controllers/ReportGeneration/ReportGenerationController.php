<?php

namespace App\Http\Controllers\ReportGeneration;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\ReportGeneration\{
    GenerateRevenueReportRequest
};

// * REPOSITORY
use App\Repositories\ReportGeneration\{
    GenerateRevenueReportRepository
};

class ReportGenerationController extends Controller
{
    protected $revenueReport;

    public function __construct(
        GenerateRevenueReportRepository $revenueReport
    ) {
        $this->revenueReport = $revenueReport;
    }

    protected function revenueReport(GenerateRevenueReportRequest $request)
    {
        return $this->revenueReport->execute($request);
    }
}
