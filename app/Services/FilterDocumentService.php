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

use Validator;

class FilterDocumentService
{
    
    public function filterDocuments($typeId = null, $numberOfPages = null, $state = null, $district = null, $village = null,$locker_no=null,$old_locker_no=null,$number_of_pages=null): Collection
    {
        $query = Master_doc_data::query();

        if ($typeId) {
            $query->where('document_type', explode('|', $typeId)[0]);
        }

        if ($locker_no) {
            $query->where('locker_id', $locker_no);
        }
        if ($old_locker_no) {
            $query->where('old_locker_number', $old_locker_no);
        }
        // dd($number_of_pages);
        if ($number_of_pages) {
            $query->where('number_of_page', '<=', $number_of_pages);
        }
    
        if ($state) {
            $query->where(function ($q) use ($state) {
                $q->where('state', $state)
                  ->orWhere('current_state', $state)
                  ->orWhere('alternate_state', $state);
            });
        }
        if ($district) {
            $query->where(function ($q) use ($district) {
                $q->where('district', $district)
                  ->orWhere('current_district', $district)
                  ->orWhere('alternate_district', $district);
            });
        }
        if ($village) {
            $query->where(function ($q) use ($village) {
                $q->where('village', $village)
                  ->orWhere('current_village', $village)
                  ->orWhere('alternate_village', $village);
            });
        }
        // return $query->get();
       

        $filteredData = $query->get();
        foreach ($filteredData as $item) {
            $documentType = $item->document_type_name;
    
            // Determine the corresponding table name based on documentType
            $tableName = $documentType;
            // Query the corresponding table using masterDocId
            $tableEntry = DB::table($tableName)
                ->where('doc_id', $item->id)
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