<?php

namespace App\Http\Controllers;
use App\Services\DashboardService;


class Dashboard extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboard()
    {
        $documentCount = $this->dashboardService->getDocumentCounts();
        $getGeographicalCounts = $this->dashboardService->getGeographicalCounts();
        $getRecieverCount = $this->dashboardService->getRecieverCount();
        

        $documentTypeWiseCounts = $this->dashboardService->getDocumentCountsByType();
        // dd($documentTypeWiseCounts);
        return view('dashboard', compact('documentCount','getRecieverCount','documentTypeWiseCounts','getGeographicalCounts'));

    }
}