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

class FilterDocumentController extends Controller
{
    protected $filterdocumentService;

    public function __construct(FilterDocumentService $filterdocumentService)
    {
        $this->filterdocumentService = $filterdocumentService;
    }

    
    public function filterDocument(Request $request)
    {
        $documents = collect();
        $typeId = $request->input('type');
        $state = $request->input('state');
        $district = $request->input('district');
        $village = $request->input('village');
        // $locker_no = $request->input('locker_no');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $area_range_start = $request->input('area_range_start');
        $area_range_end = $request->input('area_range_end');
        $area_unit = $request->input('area_unit');
        $court_case_no = $request->input('court_case_no');
        $doc_no = $request->input('doc_no');
        $survey_no = $request->input('survey_no');
        // $category = $request->input('category');
        $doc_name = $request->input('doc_name');
        $doc_identifier_id = $request->input('doc_identifiers');
        $locker_id = $request->input('locker_id');
        $category_id = $request->input('categories');
        $subcategory_id = $request->input('subcategories');
        $doc_status = $request->input('doc_status');
        $logs = $request->input('logs');
        $request->flash();

        //     $categories = Master_doc_data::pluck('category')
        // ->reject(function ($value) {
        //     return empty($value);
        // })
        // ->unique()
        // ->values();

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
        $filters = $request->only(['type', 'number_of_pages', 'state', 'district', 'village', 'locker_no',  'start_date', 'end_date', 'area_range_start', 'area_range_end', 'area_unit', 'court_case_no', 'doc_no', 'survey_no', 'category', 'doc_name', 'doc_status', 'logs']);
        $filterSet = count(array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        }));

        $documents = $this->filterdocumentService->filterDocuments($typeId, $state, $district, $village, $start_date, $end_date, $area_range_start, $area_range_end, $area_unit, $court_case_no, $doc_no, $survey_no, $category_id, $subcategory_id, $doc_name, $doc_identifier_id, $locker_id, $doc_status, $logs, 10);
        // dd($survey_nos);
        $data = [
            'documents' => $documents,
            'doc_type' => Master_doc_type::orderBy('name')->get(),
            'selected_type' => $typeId,
            'states' => $states,
            'districts' => $districts,
            'villages' => $villages,
            'area_unit' => $area_unit,
            'categories' => $categories,
            'lockers' => $lockers,
            'docIdentifiers' => $docIdentifiers,
            'survey_nos' => $survey_nos,
            'doc_nos' => $doc_nos,
            'courtCaseNos' => $courtCaseNos,
        ];

        return view('pages.documents.filter-document', $data);
    }
    public function export(Request $request)
    {
        Log::info(['request', $request->all()]);
        dd($request->all());
        $filters = json_decode($request->input('filters'), true);

        // Retrieve filtered documents based on the filters
        $documents = $this->filterdocumentService->filterDocuments(
            $filters['type'],
            $filters['state'],
            $filters['district'],
            $filters['village'],
            $filters['start_date'],
            $filters['end_date'],
            $filters['area_range_start'],
            $filters['area_range_end'],
            $filters['area_unit'],
            $filters['court_case_no'],
            $filters['doc_no'],
            $filters['survey_no'],
            $filters['category'],
            $filters['doc_name'],
            $filters['doc_status'],
            $filters['logs'],
            null // No pagination for export
        );

        // Define the columns you want to include in the CSV
        $columns = [
            'column1_name',
            'column2_name',
            'column3_name',
            // Add more columns as needed
        ];

        // Create a CSV file
        $csvContent = implode(',', $columns) . "\n";

        foreach ($documents as $document) {
            $csvContent .= implode(',', [
                $document->column1_name,
                $document->column2_name,
                $document->column3_name,
                // Add more columns as needed
            ]) . "\n";
        }

        $fileName = 'documents_export_' . date('Y-m-d_H-i-s') . '.csv';

        // Save the CSV file to the storage
        Storage::disk('local')->put($fileName, $csvContent);

        // Return the CSV file as a download response
        return Response::download(storage_path("app/{$fileName}"), $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
