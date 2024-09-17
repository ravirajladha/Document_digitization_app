<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FilterDocumentService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\{Master_doc_type, Master_doc_data, Category};
use App\Exports\DocumentsExport;
use Maatwebsite\Excel\Facades\Excel;
class FilterDocumentController extends Controller
{
    protected $filterdocumentService;

    public function __construct(FilterDocumentService $filterdocumentService)
    {
        $this->filterdocumentService = $filterdocumentService;
    }

    public function filterDocument(Request $request)
    {
        $filters = $request->only([
            'type', 'number_of_pages', 'state', 'district', 'village', 'locker_no', 'start_date',
            'end_date', 'area_range_start', 'area_range_end', 'area_unit', 'court_case_no',
            'doc_no', 'survey_no', 'categories', 'subcategories', 'locker_ids', 'doc_identifiers', 'doc_name', 'doc_status', 'logs'
        ]);

        // Store filters in session
        session(['document_filters' => $filters]);
        
        // dd($filters);
        return $this->getFilteredDocuments($filters);

    }
    public function getFilteredDocuments($filters = null)
    {
        if (!$filters) {
            $filters = session('document_filters', []);
            // dd($filters);
        }

        $courtCaseNos = Master_doc_data::pluck('court_case_no')
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return empty($value);
            })
            ->values();


        $states = Master_doc_data::pluck('current_state')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return empty($value);
            })
            ->values();

        $districts = Master_doc_data::pluck('current_district')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return empty($value);
            })
            ->values();

        $villages = Master_doc_data::pluck('current_village')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    // Trim spaces after exploding
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return $value === '' || is_null($value);
            })
            ->reject(function ($value) {
                // Cast Stringable to string before checking if it is empty or null
                $stringValue = (string) $value;
                return $stringValue === '' || is_null($stringValue);
            })
            ->values();

        $doc_nos = Master_doc_data::pluck('doc_no')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    // Trim spaces after exploding
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return $value === '' || is_null($value);
            })
            ->reject(function ($value) {

                $stringValue = (string) $value;
                return $stringValue === '' || is_null($stringValue);
            })
            ->values();

        $survey_nos = Master_doc_data::pluck('survey_no')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    // Trim spaces after exploding
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return $value === '' || is_null($value);
            })
            ->reject(function ($value) {
                // Cast Stringable to string before checking if it is empty or null
                $stringValue = (string) $value;
                return $stringValue === '' || is_null($stringValue);
            })
            ->values();
        $categories = Category::all();
        $lockers = Master_doc_data::whereNotNull('locker_id')
            ->where('locker_id', '!=', '')
            ->distinct()
            ->pluck('locker_id');

        $docIdentifiers = Master_doc_data::whereNotNull('doc_identifier_id')
            ->where('doc_identifier_id', '!=', '')
            ->distinct()
            ->pluck('doc_identifier_id');
        // $filters = $request->only(['type', 'number_of_pages', 'state', 'district', 'village', 'locker_no',  'start_date', 'end_date', 'area_range_start', 'area_range_end', 'area_unit', 'court_case_no', 'doc_no', 'survey_no', 'category', 'doc_name', 'doc_status', 'logs']);
        // $filterSet = count(array_filter($filters, function ($value) {
        //     return !is_null($value) && $value !== '';
        // }));

        // $documents = $this->filterdocumentService->filterDocuments($typeId, $state, $district, $village, $start_date, $end_date, $area_range_start, $area_range_end, $area_unit, $court_case_no, $doc_no, $survey_no, $category_id, $subcategory_id, $doc_name, $doc_identifier_id, $locker_id, $doc_status, $logs, 10);

        $documents = $this->filterdocumentService->filterDocuments(
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
            $filters['doc_no'] ?? null,
            $filters['survey_no'] ?? null,
            $filters['categories'] ?? null,
            $filters['subcategories'] ?? null,
            $filters['doc_name'] ?? null,
            $filters['doc_identifiers'] ?? null,
            $filters['locker_ids'] ?? null,
            $filters['doc_status'] ?? null,
            $filters['logs'] ?? null,
            10
        );


        // dd($survey_nos);
        $data = [
            'documents' => $documents,
            'doc_type' => Master_doc_type::orderBy('name')->get(),
            'selected_type' =>  $filters['type'] ?? null,
            'states' => $states,
            'districts' => $districts,
            'villages' => $villages,
            'area_unit' =>  $filters['area_unit'] ?? null,
            'categories' => $categories,
            'lockers' => $lockers,
            'docIdentifiers' => $docIdentifiers,
            'survey_nos' => $survey_nos,
            'doc_nos' => $doc_nos,
            'courtCaseNos' => $courtCaseNos,
            'filters' => $filters,
        ];

        return view('pages.documents.filter-document', $data);
    }
  
    // public function exportFilteredDocuments()
    // {
    //     $filters = session('document_filters', []);
    
    //     return Excel::download(new DocumentsExport($filters), 'filtered_documents.xlsx');
    // }


    public function exportFilteredDocuments(Request $request)
{
    dd($request->all());
    $documents = json_decode($request->input('documents'), true);

    return Excel::download(new DocumentsExport($documents), 'filtered_documents.xlsx');
}

}
