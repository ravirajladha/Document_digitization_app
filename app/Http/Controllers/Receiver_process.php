<?php

namespace App\Http\Controllers;
// namespace App\Mail;

use Illuminate\Http\Request;


use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment};
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\AssignDocumentEmail;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Log;

class Receiver_process extends Controller
{
    public function showAssignedDocument()
    {
        $documentAssignments = Document_assignment::with(['receiver', 'receiverType', 'documentType', 'document'])->orderBy('created_at', 'desc')
        ->get();

        $documentTypes = Master_doc_type::all();
        $receiverTypes = Receiver_type::where('status',1)->get();

        return view('pages.assign-documents', [
            'documentAssignments' => $documentAssignments,
            'documentTypes' => $documentTypes,
            'receiverTypes' => $receiverTypes
        ]);
    }

    public function getReceiversByType($typeId)
    {
        $receivers = Receiver::where('receiver_type_id', $typeId)->get();
        return response()->json(['receivers' => $receivers]);
    }
    public function assignDocumentsToReceiver1(Request $request)
    {
        // Validate the request...
        $validatedData = $request->validate([
            'document_type' => 'required', // Assuming this is an ID or a code
            'document_id' => 'required', // Assuming documents table exists
            'receiver_id' => 'required', // Assuming receivers table exists
            'receiver_type' => 'required', // Assuming receivers table exists
        ]);

        $token = Str::random(40); // Generate a unique token
        $expiresAt = Carbon::now()->addHours(24);

        // Create a new document assignment entry
        $assignment = Document_assignment::create([
            'document_type' => $validatedData['document_type'],
            'doc_id' => $validatedData['document_id'],
            'receiver_id' => $validatedData['receiver_id'],
            'receiver_type' => $validatedData['receiver_type'],
            'access_token' => $token,
            'expires_at' => $expiresAt,
            'created_by' => Auth::user()->id,
        ]);
        $assignment->save();
        if ($assignment->wasRecentlyCreated) {
            // Assume you have the document type id and document id from the request
            $documentTypeName = Master_doc_type::where('id', $request->document_type)->first()->name;
            $documentName = Master_doc_data::where('id', $request->document_id)->first()->name;

            // Retrieve the table and column names for file storage
            $Table_metadata = Table_metadata::where('table_id', $request->document_type)->whereIn('data_type', [3, 4, 6])->get();

            // Prepare the zip file
            $zip = new ZipArchive;
            $zipFileName = 'documents.zip';
            $path = "uploads/";
            // $zipFilePath = public_path('uploads\documents.zip');
            $zipFilePath = storage_path($path . $zipFileName);
            // if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            //     // Your logic to add files to the zip archive
            //     dd($zipFilePath);
            //     $zip->close();
            // } else {
            //     // Handle the error
            // }
            dd("hello before else");

            if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
                foreach ($Table_metadata as $metadata) {
                    $tableName = $documentTypeName; // The table where documents are stored
                    $columnName = $metadata->column_name; // The column that stores the file path

                    // Retrieve the file path from the document table and column
                    $filePath = DB::table($tableName)->where('id', $request->document_id)->value($columnName);
                    dd($filePath);
                    // Check if file exists and add it to the zip
                    // Assume $filePath is relative to the "storage/app/public" directory
                    $fullPath = storage_path($filePath);
                    if (file_exists($fullPath)) {
                        // Add file to zip
                        $zip->addFile($fullPath, basename($filePath));
                    }
                }
                $zip->close();

                // Continue with your logic to serve the zip or send it via email
            } else {
                // Handle error
                dd("hello from else");
                dd($zipFilePath);
                Log::error("Cannot create zip file: " . $zipFilePath);
            }


            // Delete the zip file after sending the email
            // if (file_exists($zipFileName)) {
            //     unlink($zipFileName);
            // }

            session()->flash('toastr', ['type' => 'success', 'message' => 'Documents assigned and emailed successfully']);
            return redirect('/assign-documents');
        } else {
            // Handle error case...
        }
    }
    public function assignDocumentsToReceiver(Request $request)
    {
        // Your validation logic here...
        // Validate the request...
        $validatedData = $request->validate([
            'document_type' => 'required', // Assuming this is an ID or a code
            'document_id' => 'required', // Assuming documents table exists
            'receiver_id' => 'required', // Assuming receivers table exists
            'receiver_type' => 'required', // Assuming receivers table exists
        ]);

        // Generate a unique token with the current timestamp
        $timestamp = Carbon::now()->timestamp;
        $token = Str::random(40) . '_' . $timestamp;
        $expiresAt = Carbon::now()->addHours(24);

        // Create a new document assignment entry
        $assignment = Document_assignment::create([
            'document_type' => $validatedData['document_type'],
            'doc_id' => $validatedData['document_id'],
            'receiver_id' => $validatedData['receiver_id'],
            'receiver_type' => $validatedData['receiver_type'],
            'access_token' => $token,
            'expires_at' => $expiresAt,
            'created_by' => Auth::user()->id,
        ]);

        if ($assignment->wasRecentlyCreated) {
            $receiver = Receiver::findOrFail($validatedData['receiver_id']);
            $receiverEmail = $receiver->email; // Assuming the 'email' column exists in the receivers table

            if (!$receiverEmail) {
                // Handle the case where the email is not set
                session()->flash('toastr', ['type' => 'error', 'message' => 'Receiver email not found.']);
                return redirect('/assign-documents');
            }

            // Continue with sending the email
            $verificationUrl = url('/verify-document/' . $token); // Define this route in your web.php

            Mail::to($receiverEmail)->send(new AssignDocumentEmail($verificationUrl, $expiresAt));

            // Redirect with success message
            session()->flash('toastr', ['type' => 'success', 'message' => 'Documents assigned successfully. Verification email sent.']);
            return redirect('/assign-documents');
        } else {
            // Handle the case where the assignment was not created
            session()->flash('toastr', ['type' => 'warning', 'message' => 'Assignment could not be created']);
            return redirect('/assign-documents');
        }
    }



    public function showPublicDocument($token)
    {
        $assignment = Document_assignment::where('access_token', $token)
            ->where('expires_at', '>', now())
            ->where('status','1')
            ->first();
            // dd($assignment->receiver->status);
            if (!$assignment || $assignment->receiver->status != '1') {
                abort(404, 'Document not found, link has expired, or receiver is inactive.');
            }
        // if (!$assignment) {
        //     abort(404, 'Document not found or link has expired.');
        // }

        $documentType = Master_doc_type::findOrFail($assignment->document_type)->name;
        $documentData = Master_doc_data::findOrFail($assignment->doc_id);

        $tableMetadata = Table_metadata::where('table_id', $assignment->document_type)
            ->whereIn('data_type', [3, 4, 6])
            ->get();

        $filePaths = [];
        foreach ($tableMetadata as $metadata) {
            $columnName = $metadata->column_name;
            $filePath = DB::table($documentType)->where('id', $assignment->doc_id)->value($columnName);
            if ($filePath) {
                $filePaths[$metadata->data_type] = $filePath;
            }
        }

        // Optionally update the database to indicate that the document has been viewed
        if (is_null($assignment->first_viewed_at)) {
            $assignment->first_viewed_at = now();
            $assignment->first_viewed_ip = request()->ip(); // Capture the IP address
        }
    
        $assignment->view_count = $assignment->view_count + 1; // Increment the view count
        $assignment->save();
    

        // Serve the document details to a view
        return view('emails.show', [
            'filePaths' => $filePaths,
            'documentName' => $documentData->name,
        ]);
    }

    public function toggleStatus(Request $request, $id)
{
    $assignment = Document_assignment::findOrFail($id);
    $assignment->status = !$assignment->status; // Toggle the status
    $assignment->save();

    return response()->json([
      
        'success' => 'Set added successfully.',
        'newStatus' => $assignment->status
    ]);
}

}
