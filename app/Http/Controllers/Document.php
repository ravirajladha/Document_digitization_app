<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Set;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\Master_doc_data;
use App\Models\Master_doc_type;
use Illuminate\Support\Facades\DB;
use App\Services\BulkUploadService;


class Document extends Controller
{
    protected $bulkUploadService;

    public function __construct(BulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function getDocumentsByType($typeId)
    {
        $documents = Master_doc_data::where('document_type', $typeId)->where('status_id', 1)->get();
        return response()->json(['documents' => $documents]);
    }

    public function bulkUploadMasterData()
    {

        $doc_type = Master_doc_type::get();
        $sets = Set::get();
        $states = State::all();

        return view('pages.documents.bulk-upload-master-data', ['doc_type' => $doc_type, 'sets' => $sets, 'states' => $states]);
    }
    public function bulkUploadMasterDocumentData(Request $request)
    {
        // dd($request->all());
        // Validate the request, e.g., ensure a file is uploaded and it's a CSV.
        $validatedData = $request->validate([
            'document' => 'required|file|mimes:csv,txt',
        ]);


        $path = $request->file('document')->getRealPath();
        $stats = $this->bulkUploadService->handleUpload($path);


        // Format your message
        $message = "Total rows processed: {$stats['total']},";
        $message .= "Inserted: {$stats['inserted']},";
        $message .= "Updated: {$stats['updated']}";
        // dd($message);
        try {
            // Call the service to handle the file upload.


            session()->flash('toastr.type', 'success');
            session()->flash('toastr.message', $message);

            // Redirect with a success message.
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr.type', 'error');
            session()->flash('toastr.message', 'Failed to upload documents: ' . $e->getMessage());

            return redirect()->back();
        }
    }
    public function bulkUploadChildDocumentData(Request $request)
    {
        // dd($request->all());
        // Validate the request, e.g., ensure a file is uploaded and it's a CSV.
        $validatedData = $request->validate([
            'document' => 'required|file|mimes:csv,txt',
        ]);
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = $file->getClientOriginalName(); // Get the original name
            $path = $file->storeAs('uploads', $filename); // Store the file and retain the original name and extension
        }

        // $path = $request->file('document')->getRealPath();
        // dd($path);
        $stats = $this->bulkUploadService->handleChildUpload($path);
        session()->flash('toastr.type', 'success');
        session()->flash('toastr.message', "Data uploaded successfully.");

        // Redirect with a success message.
        return redirect()->back();
    }
//this function fetches the documents a/c to the doc_type->state->district->village->doc_id
//comment out the log for the mannual check below
//used for assign document, compliances and in receivers to assign doc
//component created for this function as document-type-select
    public function fetchData(Request $request, $type, $id)
    {
        
        $query = DB::table('master_doc_datas');
        // \Log::info('Received type:', ['type' => $type]);
        // \Log::info('Received id:', ['id' => $id]);
        // \Log::info('Received doc_type_id:', ['doc_type_id' => $request->query('doc_type_id')]); // Use query() method
        switch ($type) {
            case 'states':
                $data = $query->where('document_type', $id)
                    ->distinct()
                    ->get(['current_state as name']); // Get unique states for the document type

                    foreach ($data as $state) {
                        $approved_documents_count = DB::table('master_doc_datas')
                            ->where('document_type', $id)
                            ->where('current_state', $state->name)
                            ->where('status_id', 1)
                            ->count();
        
                        $state->approved_documents = $approved_documents_count;
                    }

                break;
            case 'districts':
                $docTypeId = $request->query('doc_type_id');
                if (is_null($docTypeId)) {
                    // \Log::warning('doc_type_id is missing for districts request.');
                    return response()->json(['error' => 'Document type ID is required'], 400);
                }

                $data = $query->where('document_type', $request->doc_type_id)
                    ->where('current_state', $id) // $id here is the state name passed from the frontend
                    ->distinct()
                    ->get(['current_district as name']); // Get unique districts

                    foreach ($data as $district) {
                        $approved_documents_count = DB::table('master_doc_datas')
                            ->where('document_type', $request->doc_type_id)
                            ->where('current_state', $id)
                            ->where('current_district', $district->name)
                            ->where('status_id', 1)
                            ->count();
        
                        $district->approved_documents = $approved_documents_count;
                    }

                break;
                case 'villages':
                    $data = $query->where('document_type', $request->doc_type_id)
                        ->where('current_state', $request->state_name)
                        ->where('current_district', $id) // $id here is the district name passed from the frontend
                        ->distinct()
                        ->get(['current_village as name']); // Get villages
                
                    $results = [];
                
                    // Iterate over each village
                    foreach ($data as $village) {
                        // Split village names separated by commas
                        $villageNames = explode(',', $village->name);
                
                        // Iterate over each split village name
                        foreach ($villageNames as $villageName) {
                            // Trim excess spaces
                            $villageName = trim($villageName);
                
                            // Count approved documents for each village name
                            $approved_documents_count = DB::table('master_doc_datas')
                                ->where('document_type', $request->doc_type_id)
                                ->where('current_state', $request->state_name)
                                ->where('current_district', $id)
                                ->where('current_village', 'like', '%' . $villageName . '%')
                                ->where('status_id', 1)
                                ->count();
                
                            // Add village name and approved document count to results
                            $results[] = [
                                'name' => $villageName,
                                'approved_documents' => $approved_documents_count
                            ];
                        }
                    }

                return response()->json($results);

                break;
            case 'documents':
                $data = $query->where('document_type', $request->doc_type_id)
                    ->where('current_state', $request->state_name)
                    ->where('current_district', $request->district_name)
                    ->where('current_village', 'like', '%' . $id . '%')
                    ->where('status_id', 1)
                    ->get(['id as document_id', 'name as name']);

                $results = $data->map(function ($item) {
                    return ['document_id' => $item->document_id, 'name' => $item->name];
                });
                return response()->json($results);
                break;
            default:
                return response()->json([]);
        }
        // \Log::info('Data being returned:', ['data' => $data]);
        $results = $data->map(function ($item) {
            return ['id' => $item->name, 'name' => $item->name ,'approved_documents'=> $item->approved_documents];
        });

        return response()->json($results);
    }
}
