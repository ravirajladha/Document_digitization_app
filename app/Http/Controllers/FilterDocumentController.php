<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\FilterDocumentService;
use App\Models\{Master_doc_type, Master_doc_data};


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
        $locker_no = $request->input('locker_no');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $area_range_start = $request->input('area_range_start');
        $area_range_end = $request->input('area_range_end');
        $area_unit = $request->input('area_unit');
        $court_case_no = $request->input('court_case_no');
        $doc_no = $request->input('doc_no');
        $survey_no = $request->input('survey_no');
        $category = $request->input('category');
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

        $categories = Master_doc_data::pluck('category')
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
                // Cast Stringable to string before checking if it is empty or null
                $stringValue = (string) $value;
                return $stringValue === '' || is_null($stringValue);
            })
            ->values();
        // dd($doc_nos);
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

        $filters = $request->only(['type', 'number_of_pages', 'state', 'district', 'village', 'locker_no',  'start_date', 'end_date', 'area_range_start', 'area_range_end', 'area_unit', 'court_case_no', 'doc_no', 'survey_no', 'category']);
        $filterSet = count(array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        }));

        $documents = $this->filterdocumentService->filterDocuments($typeId, $state, $district, $village, $locker_no, $start_date, $end_date, $area_range_start, $area_range_end, $area_unit, $court_case_no, $doc_no, $survey_no, $category);
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
            'survey_nos' => $survey_nos,
            'doc_nos' => $doc_nos,
            'courtCaseNos' => $courtCaseNos,
        ];

        return view('pages.documents.filter-document', $data);
    }
}
