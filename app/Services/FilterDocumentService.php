<?php
// File: app/Services/DocumentTableService.php

namespace App\Services;
use Carbon\Carbon;
use App\Models\Master_doc_data;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class FilterDocumentService
{

    public function filterDocuments($typeId = null, $state = null, $district = null, $village = null, $locker_no = null, $start_date = null, $end_date = null, $area_range_start = null, $area_range_end = null, $area_unit = null, $court_case_no = null, $doc_no = null, $survey_no = null,$category = null): Collection
    {
        $query = Master_doc_data::query();

        if ($typeId) {
            $query->where('document_type', explode('|', $typeId)[0]);
        }
        if ($locker_no) {
            $query->where('locker_id', $locker_no);
        }
        if ($court_case_no) {
            $query->where('court_case_no', 'like', '%' . $court_case_no . '%');
        }
        if ($category) {
            $query->where('category', $category);
        }
    
        if ($doc_no) {
            $query->where('doc_no',  $doc_no );
        }
    
        if ($survey_no) {
            $query->where('survey_no', $survey_no );
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
                $q->whereRaw("FIND_IN_SET(?, REPLACE(current_village, ' ', ''))", [$village]);
            });
        }

        $area_range_start  = intval($area_range_start);
        $area_range_end  = intval($area_range_end);
// dd(232323);
        if ($area_range_start !== null || $area_range_end !== null) {
            $query->where(function ($q) use ($area_range_start, $area_range_end, $area_unit) {
                if ($area_unit) {
                    if ($area_unit === 'Acres') {
                        // dd(23);
                        // dd($area_range_start,$area_range_end);
                        // Search for both acres and cents
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'acres and cents')
                                ->where('area', '>=', $area_range_start)
                                ->where('area', '<=', $area_range_end);
                        });
                        // Convert acres to square feet and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'Square Feet')
                                ->where('area', '>=', $area_range_start * 43560)
                                ->where('area', '<=', $area_range_end * 43560);
                        });
                    } elseif ($area_unit === 'Square Feet') {
                        // Search for square feet
                        // dd(22);
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'Square Feet')
                                ->where('area', '>=', $area_range_start)
                                ->where('area', '<=', $area_range_end);
                        });
                        // Convert square feet to acres and cents and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'acres and cents')
                                ->where('area', '>=', $area_range_start / 43560)
                                ->where('area', '<=', $area_range_end / 43560);
                        });
                    }
                }
            });
        }
        
        Log::info('Generated SQL Query: ' . $query->toSql());


        $filteredData = $query->get();
        // dd($filteredData);
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
