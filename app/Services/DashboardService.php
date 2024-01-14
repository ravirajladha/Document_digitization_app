<?php 
// File: app/Services/DashboardtService.php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment};

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

    public function getDocumentCountsByType() {
        $docTypes = DB::table('master_doc_types')->pluck('name');
        $chartLabels = []; //for email pie chart
        $chartCounts = [];//for email pie chart
        $acceptedCounts = []; //to get the accepted doc
        $notAcceptedCounts = []; // to get the pending doc
        foreach ($docTypes as $docType) {
            $existsInMasterDocData = Master_doc_data::where('document_type_name', $docType)->exists();
            if ($existsInMasterDocData && Schema::hasTable($docType)) {
            $count = $existsInMasterDocData ? DB::table($docType)->count() : 0;
            $chartLabels[] = ucfirst($docType);
            $chartCounts[] = $count;


              // Accepted count
              $notAcceptedCount  = DB::table($docType)->where('status', '<>', 1)->count();
              $notAcceptedCounts[] = $notAcceptedCount;

                // Accepted count
            $acceptedCount = DB::table($docType)->where('status', 1)->count();
            $acceptedCounts[] = $acceptedCount;
            }else{
                $chartCounts[] = 0;
$acceptedCounts[] = 0;
$notAcceptedCounts[] = 0;
            }
        }
  $total_document_type = count($chartLabels);
        $colors = $this->generateColorPalette(count($chartLabels));
        return compact('chartLabels', 'chartCounts','colors','acceptedCounts', 'notAcceptedCounts','total_document_type');
    }
    private  function generateColorPalette($numColors) {
        $colors = [];
        $hueStep = 360 / $numColors;
    
        for ($i = 0; $i < $numColors; $i++) {
            $hue = $i * $hueStep;
            $colors[] = "hsl(" . $hue . ", 70%, 60%)"; // Adjust saturation and lightness as needed
        }
    
        return $colors;
    }
    public function getGeographicalCounts() {
        $villageCount = Master_doc_data::distinct('village')->count('village');
        $talukCount = Master_doc_data::distinct('taluk')->count('taluk');
        $districtCount = Master_doc_data::distinct('district')->count('district');
        // $totalArea = Master_doc_data::sum('area');

        return [
            'villageCount' => $villageCount,
            'talukCount' => $talukCount,
            'districtCount' => $districtCount
            // 'totalArea' => $totalArea
        ];
    }

}

?>