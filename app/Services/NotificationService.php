<?php 
// File: app/Services/NotificationService.php

namespace App\Services;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;
use App\Models\Property;
use App\Services\DocumentTableService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment,Compliance,Notification};



class NotificationService
{


    public function createComplianceNotification($type, Compliance $compliance)
{
   
    $documentName = $compliance->document->name ?? 'Unknown document type';
    

    // dd($type,$compliance);
    $message = "A compliance has been {$type} named {$compliance->name} on document  {$documentName}";

    if ($type === "updated") {
        $status = $compliance->status == 1 ? "Settled" : "Cancelled";
        $message = "A compliance has been {$type} named {$compliance->name}  on ducument {$documentName} with status {$status}";
    }

    Notification::create([
        'type' => $type,
        'message' => $message,
        'compliance_id' => $compliance->id,
        'created_by' => Auth::user()->id
    ]);
}
    public function createDocumentAssignmentNotification($type, Document_assignment $assignment)
{

    
    $assignment->load('receiver', 'documentType');
    
    $receiverName = $assignment->receiver->name ?? 'Unknown receiver';
    $documentName = $assignment->document->name ?? 'Unknown document';
    $message = "A Document has been {$type} named {$documentName} to {$receiverName}";



    if ($type === "updated") {
        $status = $assignment->status == 1 ? "Active" : "Deactive";
        $message = "A Document has been {$type} named {$documentName} assigned to {$receiverName} with status {$status}";
    }

    Notification::create([
        'type' => $type,
        'message' => $message,
        'document_assignment_id' => $assignment->id,
        'created_by' => Auth::user()->id
    ]);
}



}
?>