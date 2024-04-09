<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Services\DocumentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\DocumentTableService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment, Compliance, Set, State, DocumentStatusLog};

class DocumentController extends Controller
{


    public function document_type()
    {
        // Fetch all document types
        $doc_types = Master_doc_type::orderBy('name')->get();

        // Create an empty array to store counts
        $doc_counts = [];

        // Loop through each document type and fetch the count of records in master_doc_data
        foreach ($doc_types as $doc_type) {
            $count = Master_doc_data::where('document_type', $doc_type->id) // Assuming 'id' is the foreign key in master_doc_data referring to document_type
                ->count();

            // Add the count to the array with document type name as the key
            $doc_counts[$doc_type->id] = $count;
        }
        // throw new \Exception("This is a test exception.");

        return view('pages.document_type', ['doc_types' => $doc_types, 'doc_counts' => $doc_counts]);
    }

    public function addDocumentType(Request $req, DocumentTableService $documentTypeService)
    {
        // First, validate the request data to ensure 'type' is provided
        $validatedData = $req->validate([
            'type' => 'required|string', // Add other validation rules as needed
        ]);

        // Call a method of the service to create the document type
        $result = $documentTypeService->createDocumentType($validatedData['type']);

        // Assuming the service returns a boolean indicating success or failure
        if ($result->wasRecentlyCreated) {
            session()->flash('toastr', ['type' => 'success', 'message' => 'Document Type Created Successfully']);
            return redirect('/document_type');
        } else {
            session()->flash('toastr', ['type' => 'warning', 'message' => 'Duplicate Document Type Found']);

            return redirect('/document_type')->with('error', 'Table already exists.');
        }
    }




    public function add_document_first()
    {
        $doc_type = Master_doc_type::orderBy('name')->get();
        $sets = Set::get();
        $states = State::all();

        return view('pages.add_document_first', ['doc_type' => $doc_type, 'sets' => $sets, 'states' => $states]);
    }




    public function documentCreationContinue(Request $req)
    {
        // Extracting parameters from the request
        $tableName = $req->input('table_name');
        $id = $req->input('id');
        $document_data = $req->input('document_data');

        // Retrieve document metadata
        $columnMetadata = Table_metadata::where('table_name', $tableName)
            ->get();

        // Retrieve the actual document data
        $documentData = DB::table($tableName)->where('doc_id', $document_data->doc_id)->first();

        // Render the view with all the necessary data
        return view('pages.document-creation-continue', [
            'columnMetadata' => $columnMetadata,
            'documentData' => $documentData,
            'table_name' => $tableName,
            'doc_id' => $id,
            'document_data' => $documentData,

        ]);
    }


    public function add_document(Request $req)
    {
        // dd($req->all());
        $tableName = $req->type;
        $master_doc_id = $req->master_doc_id;
        // dd($master_doc_id,$tableName);
        // Check if the table exists
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }
        $existingRecord = DB::table($tableName)->where('doc_id', $master_doc_id)->first();
        // Prepare the data for update or insert
        $updateData = ['doc_type' => $tableName];
        foreach ($columns as $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at', 'status', 'doc_type', 'doc_id', 'document_name'])) {
                if ($req->hasFile($column)) {
                    $file_paths = [];
                    foreach ($req->file($column) as $input) {
                        $extension = $input->getClientOriginalExtension();
                        $filename = Str::random(4) . time() . '.' . $extension;
                        $path = $input->move('uploads', $filename);
                        $file_paths[] = 'uploads/' . $filename;
                    }
                    $updateData[$column] = implode(',', $file_paths);
                } elseif ($existingRecord && $existingRecord->$column !== null) {
                    // If no file is uploaded, keep the existing value
                    $updateData[$column] = $existingRecord->$column;
                } else {
                    // If it's not a file and there's a new value, update with the new value
                    $updateData[$column] = $req->input($column);
                }
            }
        }

        // Check if a record with this doc_id exists and update or insert accordingly
        $existingRecord = DB::table($tableName)->where('doc_id', $master_doc_id)->first();
        if ($existingRecord) {
            // Update the existing record
            $documentId =   DB::table($tableName)->where('doc_id', $master_doc_id)->update($updateData);
            // dd($documentId->id);
        } else {
            // Insert a new record with the doc_id
            $updateData['doc_id'] = $master_doc_id; // Assuming 'doc_id' is the column name
            $documentId = DB::table($tableName)->insertGetId($updateData); // This is the new
            // dd("insert");

        }
        $documentId = DB::table($tableName)->where('doc_id', $master_doc_id)->value('id');

        // dd($documentId);
        session()->flash('toastr', ['type' => 'success', 'message' => 'Document added successfully']);

        return redirect('/review_doc/' . $tableName . '/' . $documentId);
    }
    public function update_document(Request $req)
    {
        $id = $req->id;
        $tableName = $req->type;
        $status = $req->status; // Assuming this is passed in the request
        $message = $req->holdReason ?? null; // Assuming the hold message is passed in the request
        // dd($message);
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }

        $document = DB::table($tableName)->where('id', $id)->first();



        if (!$document) {
            // Handle the case where the document doesn't exist
            return redirect()->back()->withErrors(['error' => 'Document not found']);
        }

        if ($document->status == 1) {
            // Document is already approved, return with an error message
            return redirect()->back()->withErrors(['error' => 'Document is already approved']);
        }

        // Prepare data for updating the individual document table
        $updateData = ['status' => $status];

        // Update the record in the individual document table
        DB::table($tableName)->where('id', $id)->update($updateData);

        // Prepare data for updating the master document table
        $updateDataMaster = ['status_id' => $status];

        // If the status is 'Hold', add additional fields for the message and timestamp

        if ($status == 2) { // Assuming '2' represents the 'Hold' status

            $updateDataMaster['rejection_timestamp'] = now(); // Set the current timestamp
        }
        // dd($message);
        if ($status == 2 && !empty($message)) {
            // Update with hold reason if provided
            $updateDataMaster['rejection_message'] = $message;
        }

        // Get the ID of the currently authenticated user
        $userId = auth()->id(); // or Auth::id() if you are using the Facade

        // Include the user ID in the update data for the master document table
        $updateDataMaster['updated_by'] = $userId;

        // Update the status in the master document table using the doc_id from the individual document
        Master_doc_data::where('id', $document->doc_id)->update($updateDataMaster);
        $master_data   =  Master_doc_data::where('id', $id)->first();

        //this log was necessary, as the reviewer was starting the review. so for the backup, the status has been recorded in the database.
        $logData = [
            'document_id' => $document->doc_id,
            'status' => $status,
            'message' => $message ?? null,
            'created_by' => $userId,
            'temp_id' => $master_data->temp_id ?? null, // Assuming temp_id is retrieved from $document
        ];

        DocumentStatusLog::create($logData);


        session()->flash('toastr', ['type' => 'success', 'message' => 'Document status updated successfully']);

        // Redirect back with a success message or to a different page
        return redirect('/review_doc/' . $tableName . '/' . $id)->with('success', 'Document status updated successfully');
    }


    public function add_document_data(Request $req, DocumentService $documentService)
    {
        $result = $documentService->saveDocumentData($req->all());

        if ($result['status'] === 'fail') {
            return back()->withErrors($result['errors'])->withInput();
        }

        // dd($result['status']);
        // Prepare data for the redirection
        $redirectData = [
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
            // Add other data as needed
        ];


        return $this->documentCreationContinue(new Request([
            // 'columns' => $columns,
            // 'document' => $document,
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
        ]));


        // // Convert array to request for documentCreationContinue method
        // $redirectRequest = Request::create(route('your.route.name'), 'POST', $redirectData);

        // // Perform the redirection
        // return $this->documentCreationContinue($redirectRequest);

        // Handle the success case
    }

    public function updateFirstDocumentData(Request $req, $doc_id, DocumentService $documentService)
    {
        $result = $documentService->saveDocumentData($req->all(), $doc_id);

        if ($result['status'] === 'fail') {
            return back()->withErrors($result['errors'])->withInput();
        }

        session()->flash('toastr', ['type' => 'success', 'message' => 'Please fill the other details.']);

        // dd($result['status']);
        // Prepare data for the redirection
        $redirectData = [
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
            // Add other data as needed
        ];


        return $this->documentCreationContinue(new Request([
            // 'columns' => $columns,
            // 'document' => $document,
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
        ]));
    }

    public function view_doc_first()
    {
        $doc_type = Master_doc_type::get();

        return view('pages.view_doc_first', ['doc_type' => $doc_type]);
    }


    public function document_field(Request $req, $table = null)
    {
        $doc_types = Master_doc_type::all();
        $tableName = $table ?? $req->type ?? null;

        if (!$tableName || !Schema::hasTable($tableName)) {
            abort(404, 'Table not found.');
        }

        // Fetch the column details from `table_metadata` for the given table
        $columnDetails = Table_metadata::where('table_name', $tableName)
            ->orderBy('column_name')->get(['column_name', 'data_type']);

        // dd($columnDetails);
        // Pass the column details to the view instead of the direct schema columns
        return view('pages.document_field', [
            'tableName' => $tableName,
            'columnDetails' => $columnDetails,
        ]);
    }
    public function add_document_field(Request $req)
    {
        $type = strtolower($req->type);
        $fields = $req->fields; // Array of fields
        $fieldType = $req->field_type; // Array of field types corresponding to the fields
        $duplicateColumns = [];
        $documentType = Master_doc_type::where('name', $type)->lockForUpdate()->first(); // Lock the row for update
        $table_id = $documentType->id;

        if (!Schema::hasTable($type) && !$documentType->id) {
            session()->flash('toastr', ['type' => 'warning', 'message' => 'Table does not exist.']);
            return redirect('/document_field')->with('error', 'Table does not exist.');
        }

        $columns = Schema::getColumnListing($type);
        $existingMetadataColumns = Table_metadata::where('table_name', $type)->pluck('column_name')->toArray();
        $allExistingColumns = array_merge($columns, $existingMetadataColumns);

        foreach ($fields as $index => $field) {
            $columnName = strtolower(str_replace(' ', '_', $field));
            if (in_array($columnName, $allExistingColumns)) {
                $duplicateColumns[] = $columnName;
            }
        }

        if (!empty($duplicateColumns)) {
            $duplicates = implode(', ', $duplicateColumns);
            session()->flash('toastr', ['type' => 'error', 'message' => "Duplicate columns: {$duplicates}."]);
            return redirect('/document_field' . '?type=' . $type)->with('error', "Duplicate columns: {$duplicates}.");
        }

        // Perform the schema changes outside of a transaction
        Schema::table($type, function (Blueprint $table) use ($type, $fields, $fieldType, $table_id, $allExistingColumns) {
            foreach ($fields as $index => $field) {
                $columnName = strtolower(str_replace(' ', '_', $field));
                if (!in_array($columnName, $allExistingColumns)) {
                    $table->text($columnName)->nullable();
                }
            }
        });

        // Begin the transaction for metadata insertion
        DB::beginTransaction();
        try {
            foreach ($fields as $index => $field) {
                $columnName = strtolower(str_replace(' ', '_', $field));
                if (!in_array($columnName, $allExistingColumns)) {
                    // Insert the new column details into table_metadata within the transaction
                    Table_metadata::insert([
                        'table_name' => $type,
                        'table_id' => $table_id,
                        'column_name' => $columnName,
                        'data_type' => $fieldType[$index],
                        'created_by' => Auth::user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            DB::commit(); // Only commit the metadata transactions
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect('/document_field' . '?type=' . $type)->with('error', 'An error occurred while adding fields.');
        }

        session()->flash('toastr', ['type' => 'success', 'message' => 'Fields added successfully.']);
        return redirect('/document_field' . '?type=' . $type)->with('success', 'Columns added successfully.');
    }

    public function updateDocumentFieldName(Request $request, $tableName, $oldColumnName)
    {
        // Validate the request
        $validated = $request->validate([
            'newFieldName' => 'required|string|max:255',
        ]);

        $newColumnName = str_replace(' ', '_', $validated['newFieldName']);

        if (!Schema::hasTable($tableName)) {
            return back()->with('error', 'Table does not exist.');
        }

        if (!Schema::hasColumn($tableName, $oldColumnName)) {
            return back()->with('error', 'Column does not exist.');
        }

        if (Schema::hasColumn($tableName, $newColumnName)) {
            return back()->with('error', 'New column name already exists.');
        }

        $columnType = $this->getColumnType($tableName, $oldColumnName);

        try {
            // Log::info('Preparing to rename column in table ' . $tableName);

            // Rename the column outside of transaction due to possible implicit commit
            DB::statement("ALTER TABLE `$tableName` CHANGE `$oldColumnName` `$newColumnName` $columnType");

            // Log::info('Starting transaction for table ' . $tableName);
            DB::beginTransaction();

            // Update metadata within the transaction
            Table_metadata::where('table_name', $tableName)
                ->where('column_name', $oldColumnName)
                ->update(['column_name' => $newColumnName]);

            DB::commit();
            // Log::info('Transaction committed for table ' . $tableName);

            return back()->with('success', 'Field name updated successfully.');
        } catch (\Exception $e) {
            // Only roll back if a transaction is active
            if (DB::transactionLevel() > 0) {
                Log::error('Rolling back transaction for table ' . $tableName);
                DB::rollBack();
            }

            // Log::error('Error while renaming column in table ' . $tableName . ': ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the field name. ' . $e->getMessage());
        }
    }




    private function getColumnType($tableName, $columnName)
    {
        // Retrieve the column type directly from the information schema
        $columnData = DB::selectOne(
            "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
                                      WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?",
            [env('DB_DATABASE'), $tableName, $columnName]
        );

        // Return the column type or null if not found
        return $columnData ? $columnData->COLUMN_TYPE : null;
    }




    public function updateDocumentFieldName1(Request $request, $tableName, $oldColumnName)
    {
        // Validate the request
        // dd($oldColumnName);
        $validated = $request->validate([
            'newFieldName' => 'required|string|max:255',
        ]);

        // Check if the table and old column exist
        if (!Schema::hasTable($tableName)) {
            return back()->with('error', 'Table does not exist.');
        }

        if (!Schema::hasColumn($tableName, $oldColumnName)) {
            return back()->with('error', 'Column does not exist.');
        }

        Schema::table($tableName, function ($table) use ($oldColumnName, $validated) {
            $table->renameColumn($oldColumnName, $validated['newFieldName']);
        });

        // Rename the column



        try {
            DB::beginTransaction();

            // Rename the column using raw SQL statement
            Schema::table($tableName, function ($table) use ($oldColumnName, $validated) {
                $table->renameColumn($oldColumnName, $validated['newFieldName']);
            });

            // Clear table cache to prevent issues with column not found errors
            // DB::statement('FLUSH TABLES');

            // Update the column name in table_metadata
            Table_metadata::where('table_name', $tableName)
                ->where('column_name', $oldColumnName)
                ->update(['column_name' => $validated['newFieldName']]);

            DB::commit();

            return back()->with('success', 'Field name updated successfully.');
        } catch (\Exception $e) {





            // Log the error or handle it as per your application's error handling policies
            return back()->with('error', 'An error occurred while updating the field name. ' . $e->getMessage());
        }
    }

    //  * Edit Document Basic Details
    //  * 
    //  * Retrieves and displays the editing interface for basic details of a specific document.
    //  * This function checks if the requested document exists and verifies its status before proceeding.
    //  * Only documents with a status other than 1 are accessible; otherwise, a 403 Forbidden error is returned.
    //  * 
    //  * @param int $id The unique identifier of the document to be edited.
    //  * @return \Illuminate\Http\Response Returns a view with the document's basic details for editing if the document exists and is accessible.
    //  * If the document's status_id is 1 or if the document does not exist, it returns a 403 Forbidden error page.

    public function edit_document_basic_detail($id)
    {
        $doc_type = Master_doc_type::orderBy('name')->get();

        // Retrieve the document by id
        $document = Master_doc_data::where('id', $id)->first();

        // If document not found or status_id is 1, show error page
        if (!$document || $document->status_id == 1) {
            // Use abort(404) if you want a "Not Found" response
            // or abort(403) for a "Forbidden" response, depending on your preference
            return abort(403); // Or use abort(404) for a "Not Found" response
        }

        // Proceed if document exists and status_id is not 1
        $states = State::all();
        $sets = Set::all();

        return view('pages.edit_document_first', [
            'doc_type' => $doc_type,
            'document' => $document,
            'sets' => $sets,
            'states' => $states
        ]);
    }

    public function review_doc($table, $id)
    {
        $tableName = $table;
        if (Schema::hasTable($tableName)) {
            $columnMetadata = Table_metadata::where('table_name', $tableName)
                ->get()
                ->keyBy('column_name'); // This will help you to easily find metadata by column name.
        }
        // dd($id);
        $document = DB::table($tableName)->where('id', $id)->first();
        // dd($document);
        $get_document_master_data = Master_doc_data::where('id', $document->doc_id)->first();
        $document_logs = DocumentStatusLog::where("document_id", $document->doc_id)
        ->join('users', 'document_status_logs.created_by', '=', 'users.id')
        ->select('document_status_logs.*', 'users.name as creator_name')
        ->get();
    
        // dd($get_document_logs);
        // Since SQL stores set_id as text, ensure the IDs are cast to string if they are not already
        $set_ids = json_decode($get_document_master_data->set_id, true) ?? [];
        $set_ids = array_map('strval', $set_ids);
        $masterDataEntries = Master_doc_data::all()->filter(function ($entry) use ($set_ids, $document) {
            $entrySetIds = json_decode($entry->set_id, true);
            // Check if $entrySetIds is an array
            if (!is_array($entrySetIds)) {
                $entrySetIds = []; // Assign an empty array if it's not already an array
            }
            return count(array_intersect($set_ids, $entrySetIds)) > 0 && $entry->id != $document->doc_id;
        });

        //  dd($masterDataEntries);
        $matchingData[] = null;
        foreach ($masterDataEntries as $entry) {
            $tableName1 = $entry->document_type_name;
            // Fetch data from the respective table using doc_id
            $data = DB::table($tableName1)
                ->where('doc_id', $entry->id)
                ->first();

            if ($data) {
                // Add the data to the matchingData array
                $matchingData[] = $data;
            } else {
                $matchingData[] = null;
            }
        }
        //    dd($matchingData);
        $compliances = Compliance::with(['documentType', 'document'])->where('doc_id', $document->doc_id)->orderBy('created_at', 'desc')
            ->get();

        return view('pages.review_doc', [
            'columnMetadata' => $columnMetadata,
            'document' => $document,
            'tableName' => $tableName,
            'id' => $id,
            'master_data' => $get_document_master_data,
            'matchingData' => $matchingData,
            'compliances' => $compliances,
            'document_logs' => $document_logs,
        ]);
    }


    public function configure()
    {
        $receiver_type_count = Receiver_type::count();
        // dd($receiver_type_count);
        $data = [
            'receiver_type_count' => $receiver_type_count,
        ];
        return view('pages.data-sets.data-sets', $data);
 
    }

   
        public function viewUploadedDocuments()
    {
        $basePath = base_path();

    // Construct the path to the public/uploads directory relative to the base path
    $uploadsPath = $basePath . '/public/uploads/documents';
        // Get the path to the public/uploads directory
        $uploadsPath = public_path('uploads/documents');

        // Check if the uploads directory exists
        if (!File::exists($uploadsPath)) {
            return 'Uploads directory does not exist.';
        }

        // Get the list of files in the uploads directory
        $documents = File::files($uploadsPath);

        // Initialize an array to store file information
        $fileInfoList = [];
// dd($documents);
        // Iterate over each file to extract information
        foreach ($documents as $file) {
            // Get the file name
            $filename = $file->getFilename();
            
            // Get the file size
            $size = $file->getSize();
            $extension = $file->getExtension();
            
            // Get the last modified time (uploaded date)
            $uploadedDate = $file->getMTime();

            // Push the file information into the array
            $fileInfoList[] = [
                'name' => $filename,
                'size' => $size,
                'extension' => $extension,
                'uploaded_date' => date('Y-m-d H:i:s', $uploadedDate) // Format the date as needed
            ];
        }
        // dd($fileInfoList);
        return view('pages.uploaded-documents', compact('fileInfoList'));
    }

    
    public function deleteFile($filename)
    {
        $filePath = public_path('uploads/documents/' . $filename);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->back()->with('success', 'File deleted successfully.');
        }

        return redirect()->back()->with('error', 'File not found.');
    }
 
    
    public function uploadFiles(Request $request)
    {
        // Validate the uploaded files
        $request->validate([
            'files.*' => 'required|file|mimes:pdf|max:500000', // Max size in bytes (500 MB)
        ]);
        Log::info('File upload request received');
        // Process the uploaded files
        if ($request->hasFile('files')) {
            $file_paths = [];
            foreach ($request->file('files') as $file) {
                $originalFilename = $file->getClientOriginalName(); // Get the original filename
                $path = $file->move(public_path('uploads/documents'), $originalFilename); // Store the file in the specified directory
                $file_paths[] = $path;
            }
    
            // Optionally, you can save the file paths to a database or perform additional processing here
    
            // Return a success response
            session()->flash('toastr', ['type' => 'success', 'message' => 'Document uploaded successfully']);
            return response()->json(['message' => 'Files uploaded successfully', 'file_paths' => $file_paths], 200);
        }
        // Return an error response if no files were uploaded
        return response()->json(['message' => 'No files uploaded'], 400);
    }

}


// if ($request->hasFile('files')) {
//     $file_paths = [];
//     foreach ($request->file('files') as $file) {
//         // Get the original filename
//         $originalFilename = $file->getClientOriginalName();
//         // Save the file with its original filename, overwriting if it already exists
//         $path = $file->store('uploads/documents', $originalFilename, 'public');
//         // Add the file path to the list
//         $file_paths[] = $path;
//     }