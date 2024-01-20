<?php 
// File: app/Services/DocumentTableService.php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Doc_type;
use App\Models\Master_doc_type;
use App\Models\Master_doc_data;
use Carbon\Carbon;

use Validator;

class FilterDocumentService
{
    
    public function filterDocuments($typeId = null, $state = null, $district = null, $village = null,$locker_no=null,$start_date = null,$end_date =null,$area_range_start=null,$area_range_end=null,$area_unit=null): Collection
    {
        $query = Master_doc_data::query();

        if ($typeId) {
            $query->where('document_type', explode('|', $typeId)[0]);
        }
        if ($locker_no) {
            $query->where('locker_id', $locker_no);
        }
      
        // dd($start_date);
        if ($start_date && $end_date) {
            // Convert dates to Carbon instances to ensure correct format and handle any timezone issues
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay(); // Ensures the comparison includes the start of the start_date
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay(); // Ensures the comparison includes the end of the end_date
            $query->whereBetween('issued_date', [$start, $end]);
            // dd($query);
        } elseif ($start_date) {
            // If only start_date is provided
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
            $query->where('issued_date', '>=', $start);
        } elseif ($end_date) {
            // If only end_date is provided
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
            $query->where('issued_date', '<=', $end);
        }

        if ($state) {
            $query->where(function ($q) use ($state) {
                $q->whereRaw("FIND_IN_SET(?, current_state)", [$state]);
            });
        }
        if ($district) {
            $query->where(function ($q) use ($district) {
                $q->whereRaw("FIND_IN_SET(?, current_district)", [$district]);
            });
        }
        if ($village) {
            $query->where(function ($q) use ($village) {
                $q->whereRaw("FIND_IN_SET(?, current_village)", [$village]);
            });
        }

//before all the types of villages were comninely searched

        // if ($state) {
        //     $query->where(function ($q) use ($state) {
        //         $q->where('state', $state)
        //           ->orWhere('current_state', $state)
        //           ->orWhere('alternate_state', $state);
        //     });
        // }
        // if ($district) {
        //     $query->where(function ($q) use ($district) {
        //         $q->where('district', $district)
        //           ->orWhere('current_district', $district)
        //           ->orWhere('alternate_district', $district);
        //     });
        // }
        // if ($village) {
        //     $query->where(function ($q) use ($village) {
        //         $q->where('village', $village)
        //           ->orWhere('current_village', $village)
        //           ->orWhere('alternate_village', $village);
        //     });
        // }
        if ($area_range_start !== null || $area_range_end !== null) {
            $query->where(function ($q) use ($area_range_start, $area_range_end) {
                if ($area_range_start !== null) {
                    $q->where('area', '>=', $area_range_start);
                }
                if ($area_range_end !== null) {
                    $q->where('area', '<=', $area_range_end);
                }
            });
        }
    
        // Area unit type filter
        if ($area_unit !== null) {
            $query->where('unit', $area_unit);
        }
        // return $query->get();
       

        $filteredData = $query->get();
        // dd($filteredData);
        foreach ($filteredData as $item) {
            $documentType = $item->document_type_name;
    
            // Determine the corresponding table name based on documentType
            $tableName = $documentType;
            // Query the corresponding table using masterDocId
            $tableEntry = DB::table($tableName)
                ->where('doc_id', $item->id)
                ->orderBy('document_name')
                ->first();
    
            // Attach tableId to the $item
            $item->tableId = $tableEntry ? $tableEntry->id : null;
        }
    
        return $filteredData;



    }

    public function fetchDataForFilter($masterDocId)
    {
        // Step 1: Fetch document_type and id from master_doc_data table
        $masterDocData = DB::table('master_doc_data')
                           ->select('document_type', 'id')
                           ->where('id', $masterDocId)
                           ->first();

        if (!$masterDocData) {
            // Handle error: Master document data not found
            return null;
        }

        $documentType = $masterDocData->document_type;

        // Step 2: Determine table_name based on document_type
        $masterDocTypeData = DB::table('master_doc_type')
                               ->select('name')
                               ->where('id', $masterDocId)
                               ->first();

        if (!$masterDocTypeData) {
            // Handle error: Master document type data not found
            return null;
        }

        $tableName = $masterDocTypeData->name;
dd($tableName);
        // Step 3 & 4: Fetch id from the corresponding table
        $docData = DB::table($tableName)
                     ->select('id')
                     ->where('doc_id', $masterDocId) // Assuming doc_id in the corresponding table corresponds to master_doc_data id
                     ->get();

        if ($docData->isEmpty()) {
            // Handle error: No matching data found
            return null;
        }

        $ids = $docData->pluck('id')->toArray();

        // Step 5: Combine and return the results
        return [
            'masterDocId' => $masterDocId,
            'documentType' => $documentType,
            'tableName' => $tableName,
            'ids' => $ids,
        ];
    }

}

?>