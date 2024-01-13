<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master_doc_data;
use App\Models\Master_doc_type;
use App\Models\State;
use App\Models\Set;
use Illuminate\Support\Facades\Auth;
use App\Services\BulkUploadService;
use Illuminate\Support\Facades\Redirect;
class Document extends Controller
{
    protected $bulkUploadService;

    public function __construct(BulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function getDocumentsByType($typeId) {
        $documents = Master_doc_data::where('document_type', $typeId)->get();
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
        $stats =$this->bulkUploadService->handleUpload($path);
   

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


   
  
}
