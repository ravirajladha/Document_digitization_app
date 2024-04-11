<?php

namespace App\Http\Controllers;

use App\Models\{ Master_doc_type, Compliance};
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class ComplianceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function showCompliances()
    {
        // dd(Auth::user()->id);
        // dd("test");
        $compliances = Compliance::with(['documentType', 'document'])->orderBy('created_at', 'desc')
            ->get();

        $documentTypes = Master_doc_type::orderBy('name')->get();


        return view('pages.compliances.compliances', [
            'compliances' => $compliances,
            'documentTypes' => $documentTypes,

        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        try {
            $validatedData = $request->validate([
                'document_type' => 'required|exists:master_doc_types,id',
                'document_id' => 'required|exists:master_doc_datas,id',
                'name' => 'required|string|max:255',
                'due_date' => 'required|date',
                'is_recurring' => 'sometimes|boolean'
            ]);

            $compliance = new Compliance();
            $compliance->document_type = $validatedData['document_type'];
            $compliance->doc_id = $validatedData['document_id'];
            $compliance->name = $validatedData['name'];
            $compliance->due_date = $validatedData['due_date'];
            $compliance->is_recurring = $request->has('is_recurring') ? 1 : 0;

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
