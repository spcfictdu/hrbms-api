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
    GenerateRoomOccupancyReportRepository,
    GenerateDailyCashierReportRepository,
    GeneratePaymentSummaryReportRepository,
    GenerateGuestHistoryReportRepository,
    GenerateTopGuestsReportRepository,
    GenerateGuestBookingFrequencyReportRepository
};

class ReportGenerationController extends Controller
{
    protected $revenueReport, $paymentReport, $checkInOutReport, $dailyReservations, $roomOccupancy, $dailyCashier, $paymentSummary, $guestHistory, $topGuests, $guestFrequency;

    public function __construct(
        GenerateRevenueReportRepository $revenueReport,
        GeneratePaymentReportRepository $paymentReport,
        GenerateCheckInOutReportRepository $checkInOutReport,
        GenerateDailyReservationsReportRepository $dailyReservations,
        GenerateRoomOccupancyReportRepository $roomOccupancy,
        GenerateDailyCashierReportRepository $dailyCashier,
        GeneratePaymentSummaryReportRepository $paymentSummary,
        GenerateGuestHistoryReportRepository $guestHistory,
        GenerateTopGuestsReportRepository $topGuests,
        GenerateGuestBookingFrequencyReportRepository $guestFrequency
    ) {
        $this->revenueReport = $revenueReport;
        $this->paymentReport = $paymentReport;
        $this->checkInOutReport = $checkInOutReport;
        $this->dailyReservations = $dailyReservations;
        $this->roomOccupancy = $roomOccupancy;
        $this->dailyCashier = $dailyCashier;
        $this->paymentSummary = $paymentSummary;
        $this->guestHistory = $guestHistory;
        $this->topGuests = $topGuests;
        $this->guestFrequency = $guestFrequency;
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

    protected function dailyCashier(Request $request)
    {
        return $this->dailyCashier->execute($request);
    }

    protected function paymentSummary(Request $request)
    {
        return $this->paymentSummary->execute($request);
    }

    protected function guestHistory($guestId)
    {
        return $this->guestHistory->execute($guestId);
    }

    protected function topGuests(Request $request)
    {
        return $this->topGuests->execute($request);
    }

    protected function guestFrequency(Request $request)
    {
        return $this->guestFrequency->execute($request);
    }
}
