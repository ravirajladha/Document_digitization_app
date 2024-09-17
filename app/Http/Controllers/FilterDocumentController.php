<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\{Master_doc_type, Master_doc_data, Category};
use App\Exports\DocumentsExport;
use Maatwebsite\Excel\Facades\Excel;

class FilterDocumentController extends Controller
{
    public function filterDocument(Request $request)
    {
        $filters = $request->only([
            'type', 'number_of_pages', 'state', 'district', 'village', 'locker_no', 'start_date',
            'end_date', 'area_range_start', 'area_range_end', 'area_unit', 'court_case_no','advocate_name','case_result',
            'doc_no', 'survey_no', 'categories', 'subcategories', 'locker_ids', 'doc_identifiers', 'doc_name', 'doc_status', 'logs'
        ]);

        // Store filters in session
        session(['document_filters' => $filters]);

        return $this->getFilteredDocuments($filters);
    }

    public function getFilteredDocuments($filters = null)
    {
        if (!$filters) {
            $filters = session('document_filters', []);
        }


        $caseResults = Master_doc_data::pluck('case_result')->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $advocateNames = Master_doc_data::pluck('advocate_name')->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $courtCaseNos = Master_doc_data::pluck('court_case_no')->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $states = Master_doc_data::pluck('current_state')->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $districts = Master_doc_data::pluck('current_district')->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $villages = Master_doc_data::pluck('current_village')->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $doc_nos = Master_doc_data::pluck('doc_no')->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $survey_nos = Master_doc_data::pluck('survey_no')->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))->unique()->sort()->reject(fn ($value) => empty($value))->values();
        $categories = Category::all();
        $lockers = Master_doc_data::whereNotNull('locker_id')->where('locker_id', '!=', '')->distinct()->pluck('locker_id');
        $docIdentifiers = Master_doc_data::whereNotNull('doc_identifier_id')->where('doc_identifier_id', '!=', '')->distinct()->pluck('doc_identifier_id');

        // Fetch filtered documents with pagination
        $documents = $this->filterDocuments(
            $filters['type'] ?? null,
            $filters['state'] ?? null,
            $filters['district'] ?? null,
            $filters['village'] ?? null,
            $filters['start_date'] ?? null,
            $filters['end_date'] ?? null,
            $filters['area_range_start'] ?? null,
            $filters['area_range_end'] ?? null,
            $filters['area_unit'] ?? null,
            $filters['court_case_no'] ?? null,
             $filters['advocate_name'] ?? null,
        
          $filters['case_result'] ?? null,
            $filters['doc_no'] ?? null,
            $filters['survey_no'] ?? null,
            $filters['categories'] ?? null,
            $filters['subcategories'] ?? null,
            $filters['doc_name'] ?? null,
            $filters['doc_identifiers'] ?? null,
            $filters['locker_ids'] ?? null,
            $filters['doc_status'] ?? null,
            $filters['logs'] ?? null,
            10 // Set pagination here
        );

        // Prepare data for the view
        $data = [
            'documents' => $documents,
            'doc_type' => Master_doc_type::orderBy('name')->get(),
            'selected_type' => $filters['type'] ?? null,
            'states' => $states,
            'districts' => $districts,
            'villages' => $villages,
            'area_unit' => $filters['area_unit'] ?? null,
            'categories' => $categories,
            'lockers' => $lockers,
            'docIdentifiers' => $docIdentifiers,
            'survey_nos' => $survey_nos,
            'doc_nos' => $doc_nos,
            'courtCaseNos' => $courtCaseNos,
            'advocateNames' => $advocateNames,
            'caseResults' => $caseResults,
            'filters' => $filters,
        ];

        return view('pages.documents.filter-document', $data);
    }

    public function filterDocuments(
        $typeId = null,
        $state = null,
        $district = null,
        $village = null,
        $start_date = null,
        $end_date = null,
        $area_range_start = null,
        $area_range_end = null,
        $area_unit = null,
        $court_case_no = null,
        $advocate_name = null,
        $case_result = null,
        $doc_no = null,
        $survey_no = null,
        $category_id = null,
        $subcategory_id = null,
        $doc_name = null,
        $doc_identifier_id = null,
        $locker_id = null,
        $doc_status = null,
        $logs = null,
        $perPage = 10
    ) {
        // Build the query with all the filters
        $query = Master_doc_data::query();

        // Apply filters to the query
        if ($typeId) {
            $query->where('document_type', explode('|', $typeId)[0]);
        }
        if ($court_case_no) {
            $query->where('court_case_no', 'like', '%' . $court_case_no . '%');
        }
        if ($advocate_name) {
            $query->where('advocate_name', 'like', '%' . $advocate_name . '%');
        }
        if ($case_result) {
            $query->where('case_result', 'like', '%' . $case_result . '%');
        }
        if ($locker_id) {
            $lockerNos = is_array($locker_id) ? $locker_id : explode(',', $locker_id);
            $query->where(function ($q) use ($lockerNos) {
                foreach ($lockerNos as $locker_id) {
                    $q->orWhere('locker_id', 'like', '%' . $locker_id . '%');
                }
            });
        }
        if ($doc_identifier_id) {
            $docIdentifiers = is_array($doc_identifier_id) ? $doc_identifier_id : explode(',', $doc_identifier_id);
            $query->where(function ($q) use ($docIdentifiers) {
                foreach ($docIdentifiers as $docIdentifier) {
                    $q->orWhere('doc_identifier_id', 'like', '%' . $docIdentifier . '%');
                }
            });
        }
        if ($category_id) {
            $categories = is_array($category_id) ? $category_id : explode(',', $category_id);
            $query->where(function ($q) use ($categories) {
                foreach ($categories as $cat) {
                    $q->orWhereRaw("FIND_IN_SET(?, category_id)", [$cat]);
                }
            });
        }
        if ($subcategory_id) {
            $subcategories = is_array($subcategory_id) ? $subcategory_id : explode(',', $subcategory_id);
            $query->where(function ($q) use ($subcategories) {
                foreach ($subcategories as $subcat) {
                    $q->orWhereRaw("FIND_IN_SET(?, subcategory_id)", [$subcat]);
                }
            });
        }
        if ($doc_no) {
            $query->where('doc_no', $doc_no);
        }
        if ($doc_status !== null) {
            $query->where('status_id', $doc_status);
        }
        if ($logs) {
            $query->where('transaction_type', $logs);
        }
        if ($doc_name) {
            $query->where('name', 'like', '%' . $doc_name . '%');
        }
        if ($survey_no) {
            $query->where('survey_no', 'like', '%' . $survey_no . '%');
        }
        if ($start_date && $end_date) {
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
            $query->whereBetween('issued_date', [$start, $end]);
        } elseif ($start_date) {
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
            $query->where('issued_date', '>=', $start);
        } elseif ($end_date) {
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
            $query->where('issued_date', '<=', $end);
        }
        if ($state) {
            $query->whereRaw("FIND_IN_SET(?, current_state)", [$state]);
        }
        if ($district) {
            $query->whereRaw("FIND_IN_SET(?, current_district)", [$district]);
        }
        if ($village) {
            $query->where('current_village', 'like', '%' . $village . '%');
        }
        if ($area_range_start !== null || $area_range_end !== null) {
            $query->where(function ($q) use ($area_range_start, $area_range_end, $area_unit) {
                if ($area_unit) {
                    if ($area_unit === 'Acres') {
                        // Search for both acres and cents
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'acres and cents');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start, $area_range_end]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end);
                            }
                        });

                        // Convert acres to square feet and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'Square Feet');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start * 43560, $area_range_end * 43560]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start * 43560);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end * 43560);
                            }
                        });
                    } elseif ($area_unit === 'Square Feet') {
                        // Search for square feet
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'Square Feet');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start, $area_range_end]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end);
                            }
                        });

                        // Convert square feet to acres and cents and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'acres and cents');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start / 43560, $area_range_end / 43560]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start / 43560);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end / 43560);
                            }
                        });
                    }
                }
            });
        }

        Log::info('SQL Query', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

        // Fetch the filtered data
        $filteredData = $perPage ? $query->paginate($perPage) : $query->get();
// dd($filteredData);
        // Loop through the filtered data to attach related table IDs
        foreach ($filteredData as $item) {
            $documentType = $item->document_type_name;

            // Determine the corresponding table name based on documentType
            $tableName = $documentType;

            try {
                // Query the corresponding table using masterDocId
                $tableEntry = DB::table($tableName)
                    ->where('doc_id', $item->id)
                    ->orderBy('document_name')
                    ->first();

                // Attach tableId to the $item
                $item->tableId = $tableEntry ? $tableEntry->id : null;
            } catch (\Exception $e) {
                // Log the exception if the table does not exist or query fails
                Log::error("Failed to query table: {$tableName}. Error: " . $e->getMessage());
                $item->tableId = null;
            }
        }

        return $filteredData;
    }

    public function exportFilteredDocuments(Request $request)
    {

        // dd($request->all());
        // Retrieve filters from the session or the request
        $filters = session('document_filters', []);

        // Fetch all data based on filters without pagination
        $documents = $this->filterDocuments(
            $filters['type'] ?? null,
            $filters['state'] ?? null,
            $filters['district'] ?? null,
            $filters['village'] ?? null,
            $filters['start_date'] ?? null,
            $filters['end_date'] ?? null,
            $filters['area_range_start'] ?? null,
            $filters['area_range_end'] ?? null,
            $filters['area_unit'] ?? null,
            $filters['court_case_no'] ?? null,
            $filters['advocate_name'] ?? null,
            $filters['case_result'] ?? null,
            $filters['doc_no'] ?? null,
            $filters['survey_no'] ?? null,
            $filters['categories'] ?? null,
            $filters['subcategories'] ?? null,
            $filters['doc_name'] ?? null,
            $filters['doc_identifiers'] ?? null,
            $filters['locker_ids'] ?? null,
            $filters['doc_status'] ?? null,
            $filters['logs'] ?? null,
            null // Pass null to fetch all records instead of limiting to pagination size
        );

        // Convert the collection to an array if needed for the export
        $documentsArray = $documents->toArray();

        // Export the full dataset to Excel
        return Excel::download(new DocumentsExport($documentsArray), 'filtered_documents.xlsx');
    }
}
