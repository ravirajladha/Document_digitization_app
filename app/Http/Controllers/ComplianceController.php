<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Models\{ Master_doc_type, Compliance,Master_doc_data};

class ComplianceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function showCompliances(Request $request)
    {
        // Start building the base query
        $query = Compliance::select('compliances.*', 'master_doc_datas.name as document_name', 'master_doc_types.name as document_type_name')
            ->leftJoin('master_doc_datas', 'compliances.doc_id', '=', 'master_doc_datas.id')
            ->leftJoin('master_doc_types', 'master_doc_datas.document_type_name', '=', 'master_doc_types.id') // Assuming the relationship is through document_type_name column
            ->orderBy('compliances.created_at', 'desc');
    
        // Filter by document name
        if ($request->has('document_name')) {
            $query->where('master_doc_datas.name', 'like', '%' . $request->document_name . '%');
        }
    
        // Filter by document type name
        if ($request->has('document_type_name')) {
            $query->where('master_doc_types.name', 'like', '%' . $request->document_type_name . '%');
        }
    
        // Filter by start due date
        // if ($request->has('start_due_date')) {
        //     $startDate = $request->start_due_date;
        //     dd($startDate);
        //     $query->whereDate('compliances.due_date', '>=', $startDate);
        // }
    
        // // Filter by end due date
        // if ($request->has('end_due_date')) {
        //     $endDate = $request->end_due_date;
        //     $query->whereDate('compliances.due_date', '<=', $endDate);
        // }
    
        // Filter by is_recurring
        if ($request->has('is_recurring')) {
            $query->where('compliances.is_recurring', $request->is_recurring);
        }
    
        // Filter by status
        if ($request->has('status')) {
            $query->where('compliances.status', $request->status);
        }
    
        // Execute the query
        $compliances = $query->get();
    
        // Retrieve the lists of document types for dropdowns or other UI elements
        $documentTypes = Master_doc_type::orderBy('name')->get();
        $documents = Master_doc_data::select('id', 'name')->get();
    
        // Process each compliance to retrieve the child_id
        foreach ($compliances as $compliance) {
            // No need to retrieve child_id if using joins directly
            // $compliance->child_id = ...; // Add logic if needed
        }
    
        return view('pages.compliances.compliances', [
            'compliances' => $compliances,
            'documentTypes' => $documentTypes,
            'documents' => $documents,
        ]);
    }
    
    

//     public function showCompliances()
// {
//     // Fetch all compliances with their related data
//     $compliances = Compliance::with(['documentType', 'document'])
//         ->orderBy('created_at', 'desc')
//         ->get();

//     // Retrieve the lists of document types for dropdowns or other UI elements
//     $documentTypes = Master_doc_type::orderBy('name')->get();

//     $documents = Master_doc_data::select('id','name')->get();

//     // dd($compliances);
//     // Process each compliance to retrieve the child_id
//     foreach ($compliances as $compliance) {
//         $documentTypeName = $compliance->documentType->name;
// // dd($documentTypeName);
//         // Build the table name dynamically
//         $childDocument = DB::table($documentTypeName)
//             ->where('doc_id', $compliance->doc_id)
//             ->first();

//         if ($childDocument) {
//             $compliance->child_id = $childDocument->id;
//         }
//     }
// // dd($compliances);
//     return view('pages.compliances.compliances', [
//         'compliances' => $compliances,
//         'documentTypes' => $documentTypes,
//         'documents' => $documents,
//     ]);
// }


    public function store(Request $request)
    {
     //  dd($request->all());

        try {
            $validatedData = $request->validate([
                'document_type' => 'required|exists:master_doc_types,id',
                'document_id' => 'required|exists:master_doc_datas,id',
                'name' => 'required|string|max:255',
                'due_date' => 'required|date',
                'is_recurring' => 'sometimes|boolean',
                'recurrence_months' => 'nullable|integer|min:1',
            ]);

            $compliance = new Compliance();
            $compliance->document_type = $validatedData['document_type'];
            $compliance->doc_id = $validatedData['document_id'];
            $compliance->name = $validatedData['name'];
            $compliance->due_date = $validatedData['due_date'];
            $compliance->is_recurring = $request->has('is_recurring') ? 1 : 0;
            if ($compliance->is_recurring && $request->has('recurrence_months')) {
                $compliance->recurrence_months = $request->input('recurrence_months');
            }
            $compliance->created_by = Auth::user()->id;
            $compliance->save();
            // $this->notificationService->createComplianceNotification('created', $compliance);
            session()->flash('toastr', ['type' => 'success', 'message' => 'Compliance created successfully.']);
        } catch (Exception $e) {
            // Log the error for debugging
            logger()->error('Error in creating compliance: ' . $e->getMessage());

            // Flash error message to session
            session()->flash('toastr', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }

        return back();
    }


    public function statusChangeCompliance(Request $request, $id, $action)
    {
        // \Log::info('Status change requested for compliance ID: ' . $id . ' with action: ' . $action);

        $compliance = Compliance::findOrFail($id);
        $compliance->status = $action == "settle" ? 1 : 2;
        // if ($compliance->status == 1 && $compliance->is_recurring) {
       
        //         // Create a new Compliance object
        //         $newCompliance = new Compliance();
        
        //         // Set attributes for the new Compliance object from the original
        //         $newCompliance->name = $compliance->name;
        //         $newCompliance->document_type = $compliance->document_type;
        //         $newCompliance->doc_id = $compliance->doc_id;
        //         $newCompliance->due_date = $compliance->due_date->addYear();
        //         $newCompliance->status = 0; // Assuming 'pending' is a valid status
        //         $newCompliance->is_recurring = $compliance->is_recurring;
        //         $newCompliance->created_by = 1; 
        
        //         // Save the new Compliance object
        //         $newCompliance->save();
         
        // }
        $compliance->save();
        
   
        // $this->notificationService->createComplianceNotification('updated', $compliance);

        // $this->createNotification("updated", $compliance);
        return response()->json([

            'success' => 'Status updated successfully.',
            'newStatus' => $compliance->status
        ]);
    }




  
    public function toggleIsRecurring(Request $request, $id)
    {
        $compliance = Compliance::findOrFail($id);
    
        // Deactivate the compliance
        if ($compliance->is_recurring) {
            $compliance->is_recurring = 0;
            $compliance->save();
            session()->flash('toastr', ['type' => 'error', 'message' => 'Compliance recurring deactivated successfully.']);
            return redirect()->back()->with('success', 'Compliance deactivated successfully.');
        }
    
        // Reactivate the compliance - update OTP and expiry
        else {
           
            $compliance->is_recurring = 1; // Set status to active
            $compliance->save();
    
            session()->flash('toastr', ['type' => 'success', 'message' => 'Compliance recurring reactivated successfully.']);
            return redirect()->back()->with('success', 'Compliance recurring reactivated successfully.');
        }
    }

}
