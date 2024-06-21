<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Receiver, Receiver_type, Master_doc_type,Advocate,Advocate_documents};

class AdvocateController extends Controller
{
    //receiver types function
    
    //receivers
    public function showAdvocates()
    {
        $data = Advocate::withCount('documentAssignments') // Add the count of document assignments
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.advocates.advocate.index', [
            'data' => $data,
        ]);
    }

    public function addAdvocate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^\d{10}$/',
            'email' => 'required|string|email|max:255|unique:receivers,email',
            'address' => 'required|string',
           
        ]);

        // Create a new receiver
        $receiver = new Advocate;
        $receiver->name = $request->name;
        $receiver->phone = $request->phone;
        $receiver->email = $request->email;
        $receiver->address = $request->address;
       
        $receiver->created_by = Auth::user()->id; // or Auth::user()->id;
        $receiver->save();

        // Return a JSON response indicating success
        return response()->json(['success' => 'Advocate added successfully.']);
    }

    public function updateAdvocate(Request $request)
    {
        Log::info("update advocate details", $request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:advocates,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:advocates,email,' . $request->id,
            'address' => 'required|string',
          
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $advocate = Advocate::findOrFail($request->id);
            $advocate->name = $request->name;
            $advocate->phone = $request->phone;
            $advocate->email = $request->email;
            $advocate->address = $request->address;
            $advocate->status = $request->status;
       
            // Add any additional fields you want to update here

            $advocate->save();

            return response()->json(['success' => 'Advocate updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the advocate.'], 500);
        }
    }
    public function showAssignedDocument()
    {
        $documentAssignments = Advocate_documents::with(['advocate',  'document'])->orderBy('created_at', 'desc')->get();

        $documentTypes = Master_doc_type::orderBy('name')->get();
       
        return view('pages.assign-document.assign-documents', [
            'documentAssignments' => $documentAssignments,
            'documentTypes' => $documentTypes,
        ]);
    }

//     public function showAdvocateAssignedDocument($advocateId)
//     {
//         // Filter the document assignments by the passed receiver ID
//         $documentAssignments = Advocate_documents::with(['advocate', 'document'])
//             ->where('advocate_id', $advocateId)
//             ->orderBy('created_at', 'desc')
//             ->paginate(10); 
// // dd($documentAssignments);
//         // If you still need the lists of document types and receiver types for dropdowns or other UI elements
//         $documentTypes = Master_doc_type::all();
//         $receiverTypes = Receiver_type::where('status', 1)->get();

//         // You can also get the receiver details if needed, for example to display their name on the page
//         $advocate = Advocate::find($advocateId);
       
//         return view('pages.advocates.assign-document.index', [
//             'documentAssignments' => $documentAssignments,
//             'documentTypes' => $documentTypes,
//             'receiverTypes' => $receiverTypes,
//             'advocate' => $advocate, 
//             'advocateId' => $advocateId
//         ]);
//     }
public function showAdvocateAssignedDocument($advocateId)
{
    // Filter the document assignments by the passed advocate ID
    $documentAssignments = Advocate_documents::with(['advocate', 'document.documentType'])
        ->where('advocate_id', $advocateId)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Retrieve the lists of document types and receiver types for dropdowns or other UI elements
    $documentTypes = Master_doc_type::all();
    $receiverTypes = Receiver_type::where('status', 1)->get();

    // Retrieve the advocate details
    $advocate = Advocate::find($advocateId);

    // Process each document assignment to retrieve the child_id
    foreach ($documentAssignments as $assignment) {
        $documentTypeName = $assignment->document->documentType->name;

        // Build the table name dynamically
        $childDocument = DB::table($documentTypeName)
            ->where('doc_id', $assignment->doc_id)
            ->first();

        if ($childDocument) {
            $assignment->child_id = $childDocument->id;
        }
    }

    return view('pages.advocates.assign-document.index', [
        'documentAssignments' => $documentAssignments,
        'documentTypes' => $documentTypes,
        'receiverTypes' => $receiverTypes,
        'advocate' => $advocate,
        'advocateId' => $advocateId
    ]);
}

    public function getReceiversByType($typeId)
    {
        $receivers = Receiver::where('receiver_type_id', $typeId)->get();
        return response()->json(['receivers' => $receivers]);
    }
    public function getActiveReceiversByType($typeId)
    {
        $receivers = Receiver::where('receiver_type_id', $typeId)->where('status',true)->get();
        return response()->json(['receivers' => $receivers]);
    }

    public function assignDocumentsToAdvocate(Request $request)
    {
        // Define validation rules
        $rules = [
            'document_id' => 'required|exists:master_doc_datas,id', // Assuming documents table exists
            'advocate_id' => 'required|exists:advocates,id', // Assuming advocates table exists
            'case_name' => 'nullable|string|max:255',
            'case_status' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'court_name' => 'nullable|string|max:255',
            'court_case_location' => 'nullable|string|max:255',
            'plantiff_name' => 'nullable|string|max:255',
            'defendant_name' => 'nullable|string|max:255',
            'urgency_level' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'submission_deadline' => 'nullable|date'
        ];
    
        // Validate the request
        $validatedData = $request->validate($rules);
    
        // Check advocate status
        $advocate = Advocate::find($validatedData['advocate_id']);
        if (!$advocate || $advocate->status != 1) {
            session()->flash('toastr', ['type' => 'error', 'message' => 'Advocate is not active.']);
            return redirect()->back();
        }
    $advocate_id = $validatedData['advocate_id'];
        // Create the assignment
        $assignment = Advocate_documents::create([
            'doc_id' => $validatedData['document_id'],
            'advocate_id' => $validatedData['advocate_id'],
            'case_name' => $validatedData['case_name'] ?? null,
            'case_status' => $validatedData['case_status'] ?? null,
            'start_date' => $validatedData['start_date'] ?? null,
            'end_date' => $validatedData['end_date'] ?? null,
            'court_name' => $validatedData['court_name'] ?? null,
            'court_case_location' => $validatedData['court_case_location'] ?? null,
            'plantiff_name' => $validatedData['plantiff_name'] ?? null,
            'defendant_name' => $validatedData['defendant_name'] ?? null,
            'urgency_level' => $validatedData['urgency_level'] ?? null,
            'notes' => $validatedData['notes'] ?? null,
            'submission_deadline' => $validatedData['submission_deadline'] ?? null,
            'created_by' => Auth::user()->id,
        ]);
    
       
            if ($request->location == "all" || $request->location == "user") {
                return redirect()->route('advocate.documents.assigned.show', ['advocate_id' => $validatedData['advocate_id']]);
            }elseif($request->location == "review" ) {
                return redirect()->back()->with('success', 'Assignment created successfully.');
            } else {
                return redirect()->back()->with('success', 'Assignment created successfully.');
            }
        
    }

    public function editDocumentAssignment($id)
    {
        Log::info("edit document assignment", ['id' => $id]);
        $assignment = Advocate_documents::with('document')->find($id);
        if (!$assignment) {
            return response()->json(['error' => 'Document assignment not found.'], 404);
        }
    
        return response()->json($assignment);
    }
    public function updateDocumentAssignment(Request $request, $id)
{
    // Log::info("update document assignment", ['id' => $id]);
    // Define validation rules
    $rules = [
        'case_name' => 'nullable|string|max:255',
        'case_status' => 'nullable|string|max:255',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'court_name' => 'nullable|string|max:255',
        'court_case_location' => 'nullable|string|max:255',
        'plantiff_name' => 'nullable|string|max:255',
        'defendant_name' => 'nullable|string|max:255',
        'urgency_level' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'submission_deadline' => 'nullable|date'
    ];

    // Validate the request
    $validatedData = $request->validate($rules);

    // Find the assignment
    $assignment = Advocate_documents::find($id);
    if (!$assignment) {
        return redirect()->back()->with('error', 'Document assignment not found.');
    }

    // Update the assignment
    $assignment->update($validatedData);
    return redirect()->back()->with('success', 'Assignment updated successfully.');
    // return redirect()->route('advocate.documents.assigned.show', ['advocate_id' => $assignment->advocate_id])
    //                  ->with('success', 'Assignment updated successfully.');
}

public function destroy($id)
{
    $assignment = Advocate_documents::find($id);
    if (!$assignment) {
        return redirect()->back()->with('error', 'Document assignment not found.');
    }

    $assignment->delete();
    return redirect()->back()->with('success', 'Assignment deleted successfully.');

    // return redirect()->route('advocate.documents.assigned.show', ['advocate_id' => $assignment->advocate_id])
    //                  ->with('success', 'Assignment deleted successfully.');
}
public function bulkUploadAdvocateAssignDocument(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:csv,txt|max:10240', // Adjust max file size as needed
    ]);

    $filePath = $request->file('document')->getRealPath();
    $file = fopen($filePath, 'r');

    // Skip the first row (assuming it contains headers)
    fgetcsv($file);

    DB::beginTransaction();

    try {
        while (($line = fgetcsv($file)) !== false) {
            if (!empty($line[1])) { // Use the second column (index 1) as the temp_id
                if (array_filter($line)) {
   // Convert dates from dd/mm/yyyy or dd-mm-yyyy to yyyy-mm-dd
   $startDate = $this->convertDateFormat($line[4]);
   $endDate = $this->convertDateFormat($line[5]);
   $submissionDeadline = $this->convertDateFormat($line[12]);
                    // Extract data from each row, adjusting indexes as necessary
                    $data = [
                        'case_name' => $line[2] ?? null,
                        'case_status' => $line[3] ?? null,
                       'start_date' => $startDate,
                        'end_date' => $endDate,
                        'court_name' => $line[6] ?? null,
                        'court_case_location' => $line[7] ?? null,
                        'plantiff_name' => $line[8] ?? null,
                        'defendant_name' => $line[9] ?? null,
                        'urgency_level' => $line[10] ?? null,
                        'notes' => $line[11] ?? null,
                        'submission_deadline' => $submissionDeadline,
                        'advocate_id' => $line[13] ?? null,
                        'status' => $line[14] ?? null,
                        'created_by' => Auth::user()->id,
                    ];

                    // Validate the data
                    $validator = Validator::make($data, [
                        'case_name' => 'nullable|string|max:255',
                        'case_status' => 'nullable|string|max:255',
                        'start_date' => 'nullable|date',
                        'end_date' => 'nullable|date',
                        'court_name' => 'nullable|string|max:255',
                        'court_case_location' => 'nullable|string|max:255',
                        'plantiff_name' => 'nullable|string|max:255',
                        'defendant_name' => 'nullable|string|max:255',
                        'urgency_level' => 'nullable|string|max:255',
                        'notes' => 'nullable|string',
                        'submission_deadline' => 'nullable|date',
                        'advocate_id' => 'required|exists:advocates,id',
                        'status' => 'nullable|boolean',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception('Validation failed for one or more rows.');
                    }

                    // Retrieve the doc_id using temp_id from master_doc_data table
                    $doc_id = DB::table('master_doc_datas')
                        ->where('temp_id', $line[1]) // Assuming the second column (line[1]) is the temp_id
                        ->value('id');

                    if (!$doc_id) {
                        throw new \Exception("Document ID not found for temp_id: {$line[1]}");
                    }

                    // Assign the doc_id to the data
                    $data['doc_id'] = $doc_id;
                    $data['created_by'] =  Auth::user()->id;

                    // Insert data into advocate_documents table
                    DB::table('advocate_documents')->insert($data);
                }
            }
        }
        DB::commit();

        // Close the file
        fclose($file);

        // Redirect or return a response
        return redirect()->back()->with('success', 'Bulk upload completed successfully.');
    } catch (\Exception $e) {
        Log::error('Bulk upload failed: ' . $e->getMessage());
        DB::rollBack();

        // Close the file
        fclose($file);

        // Redirect back with error message
        return redirect()->back()->with('error', 'Bulk upload failed. ' . $e->getMessage());
    }
}
private function convertDateFormat($date)
{
    if (!$date) {
        return null;
    }

    $dateFormats = ['d/m/Y', 'd-m-Y'];
    foreach ($dateFormats as $format) {
        try {
            return Carbon::createFromFormat($format, trim($date))->format('Y-m-d');
        } catch (\Exception $e) {
            // Continue to the next format
        }
    }

    return null; // If none of the formats match, return null
}
}
