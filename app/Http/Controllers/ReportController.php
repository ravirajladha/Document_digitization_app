<?php

namespace App\Http\Controllers;

use App\Exports\{AssignedDocumentsExport,AdvocatesExport};
use App\Models\{Receiver,Master_doc_data,Advocate};
use App\Models\Receiver_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;


class ReportController extends Controller
{
    public function documentsAssignedToReceivers(Request $request)
    {
        // dd($request->all());
        $receiverTypes = Receiver_type::all();
        $receivers = Receiver::all();

        $assigned_documents =  DB::table('document_assignments')
        ->join('master_doc_datas', 'document_assignments.doc_id', '=', 'master_doc_datas.id')
        ->select('document_assignments.doc_id', 'master_doc_datas.name as document_name')
        ->distinct()
        ->get();

        $documents = Master_doc_data::all();
        // $documents = Document::all();
    
        $query = DB::table('document_assignments')
            // ->join('documents', 'document_assignments.document_id', '=', 'documents.id')
            ->join('receivers', 'document_assignments.receiver_id', '=', 'receivers.id')
            ->join('receiver_types', 'receivers.receiver_type_id', '=', 'receiver_types.id')
            // ->join('master_doc_sheet', 'document_assignments.doc_id', '=', 'master_doc_sheet.doc_id')
            ->join('master_doc_datas', 'document_assignments.doc_id', '=', 'master_doc_datas.id');

        if ($request->filled('receiver_type')) {
            $query->where('receivers.receiver_type_id', $request->input('receiver_type'));
        }
    
        if ($request->filled('receiver_id')) {
            $query->where('document_assignments.receiver_id', $request->input('receiver_id'));
        }
    
        if ($request->filled('doc_id')) {
            $query->where('master_doc_datas.id', $request->input('doc_id'));
        }
    
        if ($request->filled('start_date')) {
            $query->whereDate('document_assignments.created_at', '>=', $request->input('start_date'));
        }
    
        if ($request->filled('end_date')) {
            $query->whereDate('document_assignments.created_at', '<=', $request->input('end_date'));
        }
    
        $assignedDocuments = $query->paginate(10, [
            'document_assignments.id as assignment_id',
            'receivers.name as receiver_name',
            'receiver_types.name as receiver_type_name',
            'document_assignments.created_at as created_at',
            DB::raw('COALESCE(NULLIF(master_doc_datas.name, ""), "--") as document_name'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.category_id, ""), "--") as category_id'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.subcategory_id, ""), "--") as subcategory_id'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.location, ""), "--") as location'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.locker_id, ""), "--") as locker_id'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.category, ""), "--") as category'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.document_type_name, ""), "--") as document_type_name'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_state, ""), "--") as current_state'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.state, ""), "--") as state'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_state, ""), "--") as alternate_state'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_district, ""), "--") as current_district'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.district, ""), "--") as district'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_district, ""), "--") as alternate_district'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_taluk, ""), "--") as current_taluk'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.taluk, ""), "--") as taluk'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_taluk, ""), "--") as alternate_taluk'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_village, ""), "--") as current_village'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.village, ""), "--") as village'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_village, ""), "--") as alternate_village'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.issued_date, ""), "--") as issued_date'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.area, ""), "--") as area'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.dry_land, ""), "--") as dry_land'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.wet_land, ""), "--") as wet_land'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.unit, ""), "--") as unit'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.old_locker_number, ""), "--") as old_locker_number'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.latitude, ""), "--") as latitude'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.longitude, ""), "--") as longitude'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.court_case_no, ""), "--") as court_case_no'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.survey_no, ""), "--") as survey_no'),
        ]);
        foreach ($assignedDocuments as $document) {
            // Check category_id and handle '--'
            if ($document->created_at) {
                $document->created_at_formatted = Carbon::parse($document->created_at)->format('d-M-Y H:i');
            } else {
                $document->created_at_formatted = '--'; // or any default value you prefer
            }
            if (isset($document->category_id) && $document->category_id !== '--' && !empty($document->category_id)) {
                $categoryIds = explode(',', $document->category_id);
                $document->category_names = DB::table('categories')
                    ->whereIn('id', $categoryIds)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $document->category_names = '--';
            }
        
            // Check subcategory_id and handle '--'
            if (isset($document->subcategory_id) && $document->subcategory_id !== '--' && !empty($document->subcategory_id)) {
                $subcategoryIds = explode(',', $document->subcategory_id);
                $document->subcategory_names = DB::table('subcategories')
                    ->whereIn('id', $subcategoryIds)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $document->subcategory_names = '--';
            }
        }
        
        
        // return($assignedDocuments);
        
    // dd($assignedDocuments);
        return view('pages.reports.documents-assigned-to-receivers', compact('receivers','receiverTypes', 'assigned_documents', 'assignedDocuments'));
    }
    
    
    

    public function documentsAssignedToReceiversExport(Request $request)
    {
        return Excel::download(new AssignedDocumentsExport($request->all()), 'assigned_documents.xlsx');
    }



    public function documentsAssignedToAdvocates(Request $request)
    {
        // dd($request->all());
      
        $advocates = Advocate::all();
        $assigned_documents = DB::table('advocate_documents')
        ->join('master_doc_datas', 'advocate_documents.doc_id', '=', 'master_doc_datas.id')
        ->select('advocate_documents.doc_id', 'master_doc_datas.name as name')
        // ->groupBy('advocate_documents.doc_id', 'master_doc_datas.name')
        ->get();
    
        // dd(count($assigned_documents));
        // $documents = Document::all();
    
        $query = DB::table('advocate_documents')
            // ->join('documents', 'document_assignments.document_id', '=', 'documents.id')
            ->join('advocates', 'advocate_documents.advocate_id', '=', 'advocates.id')
            // ->join('master_doc_sheet', 'document_assignments.doc_id', '=', 'master_doc_sheet.doc_id')
            ->join('master_doc_datas', 'advocate_documents.doc_id', '=', 'master_doc_datas.id');
    
        if ($request->filled('doc_id')) {
            $query->where('master_doc_datas.id', $request->input('doc_id'));
        }
    
        if ($request->filled('advocate_id')) {
            $query->where('advocate_documents.advocate_id', $request->input('advocate_id'));
        }
    
        if ($request->filled('start_date')) {
            $query->whereDate('advocate_documents.created_at', '>=', $request->input('start_date'));
        }
    
        if ($request->filled('end_date')) {
            $query->whereDate('advocate_documents.created_at', '<=', $request->input('end_date'));
        }
    
          // Add pagination here
    $assignedDocuments = $query->paginate(10, [
        'advocate_documents.id as assignment_id',
        'advocates.name as advocate_name',
        'advocate_documents.created_at as created_at',
        DB::raw('COALESCE(NULLIF(master_doc_datas.name, ""), "--") as document_name'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.category_id, ""), "--") as category_id'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.subcategory_id, ""), "--") as subcategory_id'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.location, ""), "--") as location'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.locker_id, ""), "--") as locker_id'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.category, ""), "--") as category'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.document_type_name, ""), "--") as document_type_name'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.current_state, ""), "--") as current_state'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.state, ""), "--") as state'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_state, ""), "--") as alternate_state'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.current_district, ""), "--") as current_district'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.district, ""), "--") as district'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_district, ""), "--") as alternate_district'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.current_taluk, ""), "--") as current_taluk'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.taluk, ""), "--") as taluk'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_taluk, ""), "--") as alternate_taluk'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.current_village, ""), "--") as current_village'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.village, ""), "--") as village'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_village, ""), "--") as alternate_village'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.issued_date, ""), "--") as issued_date'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.area, ""), "--") as area'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.dry_land, ""), "--") as dry_land'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.wet_land, ""), "--") as wet_land'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.unit, ""), "--") as unit'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.old_locker_number, ""), "--") as old_locker_number'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.latitude, ""), "--") as latitude'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.longitude, ""), "--") as longitude'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.court_case_no, ""), "--") as court_case_no'),
        DB::raw('COALESCE(NULLIF(master_doc_datas.survey_no, ""), "--") as survey_no'),
    ]);
    
    foreach ($assignedDocuments as $document) {
        // Check category_id and handle '--'
        if ($document->created_at) {
            $document->created_at_formatted = Carbon::parse($document->created_at)->format('d-M-Y H:i');
        } else {
            $document->created_at_formatted = '--'; // or any default value you prefer
        }
        if (isset($document->category_id) && $document->category_id !== '--' && !empty($document->category_id)) {
            $categoryIds = explode(',', $document->category_id);
            $document->category_names = DB::table('categories')
                ->whereIn('id', $categoryIds)
                ->pluck('name')
                ->implode(', ');
        } else {
            $document->category_names = '--';
        }
    
        // Check subcategory_id and handle '--'
        if (isset($document->subcategory_id) && $document->subcategory_id !== '--' && !empty($document->subcategory_id)) {
            $subcategoryIds = explode(',', $document->subcategory_id);
            $document->subcategory_names = DB::table('subcategories')
                ->whereIn('id', $subcategoryIds)
                ->pluck('name')
                ->implode(', ');
        } else {
            $document->subcategory_names = '--';
        }
    }
    
        // dd($assigned_documents);
    // dd($assignedDocuments);
        return view('pages.reports.documents-assigned-to-advocates', compact('advocates','assigned_documents', 'assignedDocuments'));
    }

    public function documentsAssignedToAdvocatesExport(Request $request)
    {
        return Excel::download(new AdvocatesExport($request->all()), 'advocates_assigned_documents.xlsx');
    }
}
