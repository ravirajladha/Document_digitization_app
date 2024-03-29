<?php
// File: app/Services/DashboardtService.php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment,User};

use App\Models\Doc_type;


use Validator;

class DashboardService
{
    public function getDocumentCounts()
    {
        return Master_doc_data::count();
    }
    public function getRecieverCount()
    {
        return Receiver::where('status', 1)->count();
    }

    public function getDocumentCountsByType()
    {
        $docTypes = DB::table('master_doc_types')->orderBy('name')->pluck('name');
        $chartLabels = []; //for email pie chart
        $chartCounts = []; //for email pie chart
        $acceptedCounts = []; //to get the accepted doc
        $notAcceptedCounts = []; // to get the pending doc
        $holdedCounts = []; // to get the pending doc
        $userCounts = 0;
       $userCounts =  User::where('type', "user")->count();
        foreach ($docTypes as $docType) {
            $existsInMasterDocData = Master_doc_data::where('document_type_name', $docType)->exists();
            if ($existsInMasterDocData && Schema::hasTable($docType)) {
                $count = $existsInMasterDocData ? DB::table($docType)->count() : 0;

                $formattedLabel = ucwords(str_replace('_', ' ', $docType));
                
                $chartLabels[] = $formattedLabel;
                $chartCounts[] = $count;


                // not Accepted count
                $notAcceptedCount  = DB::table($docType)->where('status', 0)->count();
                $notAcceptedCounts[] = $notAcceptedCount;

                // Accepted count
                $acceptedCount = DB::table($docType)->where('status', 1)->count();
                $acceptedCounts[] = $acceptedCount;
                // Hold  count
                $holdedCount = DB::table($docType)->where('status', 2)->count();
                $holdedCounts[] = $holdedCount;
            } else {
                $chartCounts[] = 0;
                $acceptedCounts[] = 0;
                $notAcceptedCounts[] = 0;
                $holdedCounts[] = 0;
            }
        }
        // dd(array_sum($notAcceptedCounts));
        $total_document_type = count($chartLabels);

        $colors = $this->generateColorPalette(count($chartLabels));
        return compact('chartLabels', 'chartCounts', 'colors', 'acceptedCounts', 'notAcceptedCounts', 'holdedCounts', 'total_document_type','userCounts');
    }
    private function generateColorPalette($numColors)
    {
        $colors = [];

        if ($numColors > 0) {
            $hueStep = 360 / $numColors;

            for ($i = 0; $i < $numColors; $i++) {
                $hue = $i * $hueStep;
                $colors[] = "hsl(" . $hue . ", 70%, 60%)"; // Adjust saturation and lightness as needed
            }
        } else {
            // Return a default color or empty array if there are no colors to generate
            $colors = ['#cccccc']; // A default color, could be any color of your choice
            // OR simply return an empty array if no default color is desired
            // $colors = [];
        }

        return $colors;
    }

    public function getGeographicalCounts()
    {
        // $villageCount = Master_doc_data::distinct('village')->count('village');
        $villages = Master_doc_data::pluck('village')->toArray(); // Fetch all village data
        $allVillages = [];

        foreach ($villages as $villageString) {
            if (!empty(trim($villageString))) {
                $splitVillages = explode(',', $villageString); // Split village names
                $splitVillages = array_filter($splitVillages, function ($village) {
                    return !empty(trim($village)); // Filter out empty strings
                });
                $allVillages = array_merge($allVillages, $splitVillages);
            }
        }

        $distinctVillages = array_unique($allVillages); // Remove duplicates
        $villageCount = count($distinctVillages); // Count distinct villages

        $villageCount = count($distinctVillages); // Count distinct villages

        $talukCount = Master_doc_data::distinct('taluk')->count('taluk');
        $districtCount = Master_doc_data::distinct('district')->count('district');
        // Sum of area for acres and cents
        $totalAreaAcre = Master_doc_data::where('unit', 1)->sum('area');

        // Sum of area for square feer
        $totalAreaFeet = Master_doc_data::where('unit', 2)->sum('area');

        return [
            'villageCount' => $villageCount,
            'talukCount' => $talukCount,
            'districtCount' => $districtCount,
            'totalAreaAcre' => $totalAreaAcre,
            'totalAreaFeet' => $totalAreaFeet
        ];
    }
}
