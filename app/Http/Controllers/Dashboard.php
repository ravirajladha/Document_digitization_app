<?php

namespace App\Http\Controllers;
use App\Services\DashboardService;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment};

use Illuminate\Http\Request;

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