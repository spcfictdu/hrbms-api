<?php

namespace App\Http\Controllers\ReportGeneration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    GenerateCheckInOutReportRepository,
    GenerateDailyReservationsReportRepository,
    GenerateRoomOccupancyReportRepository
};

class ReportGenerationController extends Controller
{
    protected $revenueReport, $paymentReport, $checkInOutReport, $dailyReservations, $roomOccupancy;

    public function __construct(
        GenerateRevenueReportRepository $revenueReport,
        GeneratePaymentReportRepository $paymentReport,
        GenerateCheckInOutReportRepository $checkInOutReport,
        GenerateDailyReservationsReportRepository $dailyReservations,
        GenerateRoomOccupancyReportRepository $roomOccupancy
    ) {
        $this->revenueReport = $revenueReport;
        $this->paymentReport = $paymentReport;
        $this->checkInOutReport = $checkInOutReport;
        $this->dailyReservations = $dailyReservations;
        $this->roomOccupancy = $roomOccupancy;
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

    protected function dailyReservations(Request $request)
    {
        return $this->dailyReservations->execute($request);
    }

    protected function roomOccupancy(Request $request)
    {
        return $this->roomOccupancy->execute($request);
    }
}
