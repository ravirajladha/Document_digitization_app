<?php

namespace App\Http\Controllers;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment,Compliance,Notification};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;  
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
        // dd("test");
        $compliances = Compliance::with([ 'documentType', 'document'])->orderBy('created_at', 'desc')
            ->get();

        $documentTypes = Master_doc_type::orderBy('name')->get();
        

        return view('pages.compliances', [
            'compliances' => $compliances,
            'documentTypes' => $documentTypes,
       
        ]);
    }

    public function store(Request $request)
    {
  
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
            $this->notificationService->createComplianceNotification('created', $compliance);
            session()->flash('toastr', ['type' => 'success', 'message' => 'Compliance created successfully.']);
        } catch (Exception $e) {
            // Log the error for debugging
            logger()->error('Error in creating compliance: ' . $e->getMessage());
    
            // Flash error message to session
            session()->flash('toastr', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    
        return back();
    }

    
    public function statusChangeCompliance(Request $request, $id,$action)
    {
        $compliance = Compliance::findOrFail($id);
        $compliance->status = $action =="settle" ? 1 : 2;

        $compliance->save();
        $this->notificationService->createComplianceNotification('updated', $compliance);

        // $this->createNotification("updated", $compliance);
        return response()->json([

            'success' => 'Status updated successfully.',
            'newStatus' => $compliance->status
        ]);
    }

    private function createNotification($type, Compliance $compliance)
    {
        if($type=="created"){
        $message = "A compliance has been {$type} named {$compliance->name}";

        }elseif($type="updated"){
            if($compliance->status==1){
                $status = "Settled";
            }else{
                $status = "Cancelled";

            }
        $message = "A compliance has been {$type} named {$compliance->name} with {$status} ";

        }
        
        Notification::create([
            'type' => $type,
            'message' => $message,
            'compliance_id' => $compliance->id,
            'created_by' => Auth::user()->id
        ]);
    }




}
