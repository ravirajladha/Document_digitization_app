<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;


class DashboardController extends Controller
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
        $users = $this->dashboardService->getUsersWithTodayCounts();
        $getCategoryDocumentCounts = $this->dashboardService->getCategoryDocumentCounts();
        // dd($getCategoryDocumentCounts);
//        dd($users);
        return view('pages.dashboard.dashboard', compact('documentCount', 'getRecieverCount', 'documentTypeWiseCounts', 'getGeographicalCounts','users','getCategoryDocumentCounts'));
    }
}
