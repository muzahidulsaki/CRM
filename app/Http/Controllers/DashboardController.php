<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display the Executive Admin Dashboard (SRS Section 49)
     */
    public function index()
    {
        $summary = $this->reportService->getAdminExecutiveSummary();

        return view('dashboard', compact('summary'));
    }
}
