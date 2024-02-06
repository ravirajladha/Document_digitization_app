<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Validation\Validator;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use App\Services\DocumentTableService;
use App\Services\FilterDocumentService;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Migrations\Migration;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment, Compliance, Set,State};



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
        // $numberOfPages = $request->input('number_of_pages');
        $state = $request->input('state');
        $district = $request->input('district');
        $village = $request->input('village');
        $locker_no = $request->input('locker_no');
        // $old_locker_no = $request->input('old_locker_no');
        // $number_of_pages = $request->input('number_of_pages');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // $number_of_pages_start = $request->input('number_of_pages_start');
        // $number_of_pages_end = $request->input('number_of_pages_end');
        // dd($number_of_pages_start,$number_of_pages_end);
        $area_range_start = $request->input('area_range_start');
        $area_range_end = $request->input('area_range_end');
        $area_unit = $request->input('area_unit');

        // dd($end_date);
        // Flash input to the session
        $request->flash();

        // Get unique values from the state columns
        // $states = Master_doc_data::pluck('state')
        //     ->merge(Master_doc_data::pluck('current_state'))
        //     ->merge(Master_doc_data::pluck('alternate_state'))
        //     ->unique()
        //     ->sort()
        //     ->reject(function ($value) {
        //         return empty($value);
        //     }) // Reject empty values
        //     ->values();


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



        // $districts = Master_doc_data::pluck('district')
        //     ->merge(Master_doc_data::pluck('current_district'))
        //     ->merge(Master_doc_data::pluck('alternate_district'))
        //     ->unique()
        //     ->sort()
        //     ->reject(function ($value) {
        //         return empty($value);
        //     }) // Reject empty values
        //     ->values();


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



        // $villages = Master_doc_data::pluck('village')
        //     ->merge(Master_doc_data::pluck('current_village'))
        //     ->merge(Master_doc_data::pluck('alternate_village'))
        //     ->unique()
        //     ->sort()
        //     ->reject(function ($value) {
        //         return empty($value);
        //     }) // Reject empty values
        //     ->values();


        $villages = Master_doc_data::pluck('current_village')
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



        $filters = $request->only(['type', 'number_of_pages', 'state', 'district', 'village', 'locker_no',  'start_date', 'end_date', 'area_range_start', 'area_range_end', 'area_unit']);
        $filterSet = count(array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        }));

        // if ($filterSet > 0) {
        //     if ($typeId == 'all') {
        //         $documents = Master_doc_data::paginate(15);
        //     } else {
        $documents = $this->filterdocumentService->filterDocuments($typeId, $state, $district, $village, $locker_no, $start_date, $end_date, $area_range_start, $area_range_end, $area_unit);
        // }
        // }
        // dd($documents);
        //    dd($area_unit);
        $data = [
            'documents' => $documents,
            'doc_type' => Master_doc_type::orderBy('name')->get(),
            'selected_type' => $typeId,
            // 'number_of_pages_start' => $number_of_pages_start,
            // 'number_of_pages_end' => $number_of_pages_end,
            'states' => $states,
            'districts' => $districts,
            'villages' => $villages,
            'area_unit' => $area_unit,
        ];

        return view('pages.filter-document', $data);
    }
}
