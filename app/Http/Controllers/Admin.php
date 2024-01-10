<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\Receiver_type;
use App\Models\User;
use App\Models\State;
use App\Models\Receiver;
use App\Models\Doc_type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Master_doc_data;

use App\Models\Master_doc_type;
use App\Models\Table_metadata;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Validation\Validator;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use App\Services\DocumentTableService;
use App\Services\FilterDocumentService;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Eloquent\Collection::paginate;
class Admin extends Controller
{
    protected $filterdocumentService;

    public function __construct(FilterDocumentService $filterdocumentService)
    {
        $this->filterdocumentService = $filterdocumentService;
    }

    public function document_type()
    {
        // Fetch all document types
        $doc_types = Master_doc_type::get();

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

    public function getAllDocumentsType()
    {
        $doc_types = Master_doc_type::all();

        return view('components.header', ['doc_types' => $doc_types]);
    }


    public function set()
    {
        $data = Set::get();

        return view('pages.set', ['data' => $data]);
    }
    public function addSet(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:sets', // Unique validation rule added
        ]);

        // Check for duplicate set name
        $existingSet = Set::where('name', $request->name)->first();

        if ($existingSet) {
            return response()->json(['error' => 'Set name already exists.'], 422); // Return error response for duplicate name
        }

        // Create a new set
        $set = new Set;
        $set->name = $request->name;
        $set->created_by =  Auth::user()->id;
        // Save the set to the database
        $set->save();

        return response()->json(['success' => 'Set added successfully.']);
    }

    public function updateSet(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sets,id',
            'name' => 'required|string|max:255', // Validation rules as per your requirements
        ]);

        try {
            $set = Set::findOrFail($request->id);
            $set->name = $request->name;
            $set->save();

            return response()->json(['success' => 'Set updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the set.'], 500);
        }
    }

    public function getUpdatedSets()
    {
        $sets = Set::get(); // Assuming Set is your model name
        return response()->json($sets);
    }

    //receiver types function
    public function receiverType()
    {
        $data = Receiver_type::get();
        return view('pages.receiver-type', ['data' => $data]);
    }

    public function addReceiverType(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:receiver_types', // Adjust the table name as needed
        ]);

        // Create a new receiver type
        $receiverType = new Receiver_type;
        $receiverType->name = $request->name;
        // Assign other fields as necessary
        $receiverType->created_by =  Auth::user()->id;
        // Save the receiver type to the database
        $receiverType->save();

        return response()->json(['success' => 'Receiver type added successfully.']);
    }

    public function updateReceiverType(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:receiver_types,id',
            'name' => 'required|string|max:255', // Validation rules as per your requirements
        ]);

        try {
            $receiverType = Receiver_type::findOrFail($request->id);
            $receiverType->name = $request->name;
            // Update other fields as necessary

            $receiverType->save();

            return response()->json(['success' => 'Receiver type updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the receiver type.'], 500);
        }
    }
    public function getUpdatedReceiverTypes()
    {
        $receiverTypes = Receiver_type::get(); // Assuming ReceiverType is your model name
        return response()->json($receiverTypes);
    }
    //receivers
    public function showReceivers()
    {
        $data = Receiver::with('receiverType')->get();
        $receiverTypes = Receiver_type::all();
        return view('pages.receivers', [
            'data' => $data,
            'receiverTypes' => $receiverTypes
        ]);
    }
    
    public function getUpdatedReceivers()
    {
        // Fetch receivers with the receiver type name
        $receivers = Receiver::with('receiverType')->get();
    
        // Transform the data to include the receiver type name
        $receivers = $receivers->map(function ($receiver) {
            return [
                'id' => $receiver->id,
                'name' => $receiver->name,
                'phone' => $receiver->phone,
                'city' => $receiver->city,
                'email' => $receiver->email,
                'receiver_type_name' => optional($receiver->receiverType)->name, // Get the name from the relationship
            ];
        });
    
        return response()->json($receivers);
    }
    
    
    public function addReceiver(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:receivers,email',
            'city' => 'required|string|max:255',
            'receiver_type_id' => 'required|exists:receiver_types,id',
        ]);

        // Create a new receiver
        $receiver = new Receiver;
        $receiver->name = $request->name;
        $receiver->phone = $request->phone;
        $receiver->email = $request->email;
        $receiver->city = $request->city;
        $receiver->receiver_type_id = $request->receiver_type_id;
        $receiver->created_by = Auth::user()->id; // or Auth::user()->id;
        $receiver->save();

        // Return a JSON response indicating success
        return response()->json(['success' => 'Receiver added successfully.']);
    }

    public function updateReceiver(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:receivers,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:receivers,email,' . $request->id, // Ensure email is unique except for the current receiver
            'city' => 'required|string|max:255',
            'receiver_type_id' => 'required|exists:receiver_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $receiver = Receiver::findOrFail($request->id);
            $receiver->name = $request->name;
            $receiver->phone = $request->phone;
            $receiver->email = $request->email;
            $receiver->city = $request->city;
            $receiver->receiver_type_id = $request->receiver_type_id;
            // Add any additional fields you want to update here

            $receiver->save();

            return response()->json(['success' => 'Receiver updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the receiver.'], 500);
        }
    }

    public function showAssignedDocument()
    {
        $documentTypes = Master_doc_type::all(); // Assuming you have a DocumentType model
    $receiverTypes = Receiver_type::all(); // Assuming you have a ReceiverType model

    return view('pages.assign-documents', [
        'documentTypes' => $documentTypes,
        'receiverTypes' => $receiverTypes
    ]);
    }
    public function assignDocument(Request $request)
    {
        // Validate the request...

        $token = Str::random(40); // Generate a unique token
        // $expiresAt = Carbon::now()->addHours(24); 

        $assignment = Receiver_type::create([
            'document_type' => $request->document_type,
            'doc_id' => $request->doc_id,
            'receiver_id' => $request->receiver_id,
            'access_token' => $token,
            'expires_at' => $expiresAt,
        ]);

        // Send email to the receiver with the unique link
        // The link would be something like url('/document-access/' . $token)
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

    public function add_document_field_old(Request $req)
    {
        $type = $req->type;
        // $fields = explode(',', $req->fields);
        $fields = $req->fields;
        $fieldType = $req->field_type; // Assuming field_types is a single value

        // Check if the table with the given type name exists
        if (Schema::hasTable($type)) {
            Schema::table($type, function (Blueprint $table) use ($type, $fields, $fieldType) {
                // Loop through the fields array and add columns with the specified field type
                foreach ($fields as $field) {
                    $columns = Schema::getColumnListing($type);
                    // dd(in_array($field, $columns));
                    if (in_array($field, $columns)) {
                        return redirect('/document_field' . '?type=' . $type)->with('failed', 'Columns already exist.');
                    }
                    $columnName = strtolower(str_replace(' ', '_', $field));
                    $table->text($columnName)->nullable();
                }
            });

            // Create an associative array to specify the columns and their new values
            $updateData = [];
            // dd($fields);
            foreach ($fields as $field) {
                $columnName = strtolower(str_replace(' ', '_', $field));
                $updateData[$columnName] = $fieldType; // Replace 'column1' and 'new_value1' with your column and value
            }
            $document = DB::table($type)->where('id', 1)->first();
            if (!$document) {
                $insertData = array_merge(['id' => 1], $updateData); // Set 'id' to 1 or your desired ID
                DB::table($type)->insert($insertData);
            } else {
                DB::table($type)->where('id', 1)->update($updateData);
            }
            session()->flash('toastr', ['type' => 'success', 'message' => 'Fields added Successfully']);

            return redirect('/document_field' . '?type=' . $type)->with('success', 'Columns added successfully.');
            // return redirect()->route('document_field', ['table' => $type])->with('success', 'Columns added successfully, and the first row is updated/inserted.');

        } else {
            session()->flash('toastr', ['type' => 'success', 'message' => 'Table does not exist or duplicate column found.']);

            return redirect('/document_field' . '/' . $type)->with('error', 'Table does not exist.');
        }
    }

    public function add_document_field(Request $req)
    {
        $type = strtolower($req->type);
        $fields = $req->fields; // Array of fields
        $fieldType = $req->field_type; // Array of field types corresponding to the fields
        $duplicateColumns = [];
        $documentType = Master_doc_type::where('name', $type)->first();
        $table_id = $documentType->id;
        // Check if the table with the given type name exists
        if (!Schema::hasTable($type) && !$documentType->id) {
            session()->flash('toastr', ['type' => 'warning', 'message' => 'Table does not exist.']);
            return redirect('/document_field')->with('error', 'Table does not exist.');
        }

        $columns = Schema::getColumnListing($type);

        // Check for duplicate column names before attempting to add them
        foreach ($fields as $index => $field) {
            $columnName = strtolower(str_replace(' ', '_', $field));
            if (in_array($columnName, $columns)) {
                $duplicateColumns[] = $columnName;
            }
        }

        // If there are duplicate columns, return with an error message
        if (!empty($duplicateColumns)) {
            $duplicates = implode(', ', $duplicateColumns);
            session()->flash('toastr', ['type' => 'error', 'message' => "Duplicate columns: {$duplicates}."]);
            return redirect('/document_field' . '?type=' . $type)->with('error', "Duplicate columns: {$duplicates}.");
        }


        // Add columns to the table and record them in table_metadata
        Schema::table($type, function (Blueprint $table) use ($type, $fields, $fieldType, $table_id) {
            foreach ($fields as $index => $field) {
                $columnName = strtolower(str_replace(' ', '_', $field));
                $columns = Schema::getColumnListing($type);
                if (!in_array($columnName, $columns)) {
                    // Add the column to the actual table
                    $table->text($columnName)->nullable();

                    // Insert the new column details into table_metadata
                    Table_metadata::insert([
                        'table_name' => $type,
                        'table_id' => $table_id,
                        'column_name' => $columnName,

                        'data_type' => $fieldType[$index], // Assuming fieldType is an array
                        'created_by' => Auth::user()->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        });

        session()->flash('toastr', ['type' => 'success', 'message' => 'Fields added successfully.']);
        return redirect('/document_field' . '?type=' . $type)->with('success', 'Columns added successfully.');
    }
    public function add_document_first()
    {
        $doc_type = Master_doc_type::get();
        $sets = Set::get();
        $states = State::all();

        return view('pages.add_document_first', ['doc_type' => $doc_type, 'sets' => $sets, 'states' => $states]);
    }

    public function add_document_data(Request $req)
    {

        if ($req->type == null) {
            return back();
        }
        // Validation
        $validator = Validator::make($req->all(), [
            'type' => 'required',
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $existingDocument = Master_doc_data::where('name', $req->name)->first();
        if ($existingDocument) {
            // Return back with an error message or handle as needed
            session()->flash('toastr', ['type' => 'error', 'message' => 'Document with this name already exists.']);
            return back();
        }
        // Splitting the type data
        [$id, $tableName] = explode('|', $req->type, 2);
        $selectedSets = $req->input('set');
        $setsAsString = json_encode($selectedSets);
        // Inserting data into master_doc_data
        $masterDocData = Master_doc_data::create([
            // Map your request data to your table columns
            'name' => $req->name,
            // 'type' => $req->type,
            'location' => $req->location,
            'locker_id' => $req->locker_id,
            'number_of_page' => $req->number_of_page,
            'document_type' => $id,
            'document_type_name' => $tableName,
            'current_state' => $req->current_state,
            'state' => $req->state,
            'alternate_state' => $req->alternate_state,
            'current_district' => $req->current_district,
            'district' => $req->district,
            'alternate_district' => $req->alternate_district,
            'current_taluk' => $req->current_taluk,
            'taluk' => $req->taluk,
            'alternate_taluk' => $req->alternate_taluk,
            'current_village' => $req->current_village,
            'village' => $req->village,
            'alternate_village' => $req->alternate_village,
            'issued_date' => $req->issued_date,
            'document_sub_type' => $req->document_sub_type,
            'current_town' => $req->current_town,
            'town' => $req->town,
            'alternate_town' => $req->alternate_town,
            'old_locker_number' => $req->old_locker_number,
            'physically' => $req->physically,
            'status_description' => $req->status_description,
            'review' => $req->review,
            'created_by' => Auth::user()->id,
            'set_id' => $setsAsString,
        ]);
        // dd($masterDocData->id);
        // Ensure the table exists
        if (Schema::hasTable($tableName)) {
            // Insert into dynamic table
            $newDocumentId =   DB::table($tableName)->insert([
                'doc_id' => $masterDocData->id, // Assuming 'doc_id' is the column name in the dynamic table
                'doc_type' => $tableName,
                'document_name' => $req->name,

            ]);
        }


        $document_data = DB::table($tableName)->where('doc_id', $masterDocData->id)->first();

        session()->flash('toastr', ['type' => 'success', 'message' => 'Please fill the other details.']);

        return $this->documentCreationContinue(new Request([
            // 'columns' => $columns,
            // 'document' => $document,
            'table_name' => $tableName,
            'id' => $document_data->id,
            'document_data' => $document_data
        ]));
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
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }

        $document =  DB::table($tableName)->where('id', $id)->first();

        if (!$document) {
            // Handle the case where the document doesn't exist
            return redirect()->back()->withErrors(['error' => 'Document not found']);
        }

        $updateData = ['status' => 1]; // Assuming 'status' is the column name
        $updateData1 = ['status_id' => 1]; // Assuming 'status' is the column name

        // Update the record in the individual document table
        DB::table($tableName)->where('id', $id)->update($updateData);

        // Update the status in the master document table using the doc_id from the individual document
        Master_doc_data::where('id', $document->doc_id)->update($updateData1);
        session()->flash('toastr', ['type' => 'success', 'message' => 'Document updated successfully']);

        // Redirect back with a success message or to a different page
        return redirect('/review_doc/' . $tableName . '/' . $id)->with('success', 'Document updated successfully');
    }


    public function updateFirstDocumentData(Request $req, $doc_id)
    {
        // First, find the existing document by ID
        $masterDocData = Master_doc_data::findOrFail($doc_id);

        // Validation
        $validator = Validator::make($req->all(), [
            'type' => 'required',
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Splitting the type data
        // [$id, $tableName] = explode('|', $req->type, 2);
        $tableName =  $req->type;
        $selectedSets = $req->input('set');
        $setsAsString = json_encode($selectedSets);
        // Update the master_doc_data record
        $masterDocData->update([
            'name' => $req->name,
            // 'type' => $req->type,
            'location' => $req->location,
            'locker_id' => $req->locker_id,
            'number_of_page' => $req->number_of_page,
            // 'document_type' => $id,
            // 'document_type_name' => $tableName,
            'current_state' => $req->current_state,
            'state' => $req->state,
            'alternate_state' => $req->alternate_state,
            'current_district' => $req->current_district,
            'district' => $req->district,
            'alternate_district' => $req->alternate_district,
            'current_taluk' => $req->current_taluk,
            'taluk' => $req->taluk,
            'alternate_taluk' => $req->alternate_taluk,
            'current_village' => $req->current_village,
            'village' => $req->village,
            'alternate_village' => $req->alternate_village,
            'issued_date' => $req->issued_date,
            'document_sub_type' => $req->document_sub_type,
            'current_town' => $req->current_town,
            'town' => $req->town,
            'alternate_town' => $req->alternate_town,
            'old_locker_number' => $req->old_locker_number,
            'physically' => $req->physically,
            'status_description' => $req->status_description,
            'review' => $req->review,
            // 'created_by' => Auth::user()->id,
            'set_id' => $setsAsString,
        ]);

        // Update the dynamic table record if needed
        if (Schema::hasTable($tableName)) {
            DB::table($tableName)->where('doc_id', $doc_id)->update([
                // Update the fields as necessary
                'document_name' => $req->name,
            ]);
        }

        // Get the columns and document for the view
        // $columns = Schema::getColumnListing($tableName);
        // dd($tableName);
        // $document = DB::table($tableName)->where('id', 1)->first();
        // dd($document);
        // Redirect to the view with the updated data
        $document_data = DB::table($tableName)->where('doc_id', $doc_id)->first();




        return $this->documentCreationContinue(new Request([
            // 'columns' => $columns,
            // 'document' => $document,
            'table_name' => $tableName,
            'id' => $document_data->id,
            'document_data' => $document_data
        ]));


        // return view('pages.document-creation-continue', [
        //     'columns' => $columns,
        //     'document' => $document,
        //     'table_name' => $tableName,
        //     'doc_id' => $doc_id,
        //     'document_data' => $document_data
        // ]);
    }

    public function change_password()
    {
        return view('pages.change_password');
    }
    // Auth::user()->type == "admin"
    public function update_password(Request $req)
    {
        $user = User::where('id', Auth::user()->id)->first();
        if ($user && Hash::check($req->old_pw, $user->password)) {
            $user->password = Hash::make($req->new_pw);
            $user->save();
            return redirect('/change_password')->with('success', 'Password updated Successfully.');;
        }

        return redirect('/change_password')->with('success', 'Old Password is incorrect.');;
    }
    public function view_doc_first()
    {

        $doc_type = Master_doc_type::get();

        return view('pages.view_doc_first', ['doc_type' => $doc_type]);
    }

    public function view_doc_first_submit(Request $req)
    {
        $tableName = $req->type;

        // Check if the table exists in the database
        if (!Schema::hasTable($tableName)) {
            session()->flash('toastr', ['type' => 'warning', 'message' => 'No such document found']);
            return redirect('/filter-document');
            // return redirect()->back()->with('error', 'Table does not exist.');
        }

        $document = DB::table($tableName)->get();

        // Check if the table has any data
        if ($document->isEmpty()) {
            session()->flash('toastr', ['type' => 'warning', 'message' => 'No such document found']);

            return redirect()->back()->with('error', 'No data available in the selected table.');
        }

        $columns = Schema::getColumnListing($tableName);

        // If everything is fine, redirect to the review page
        return redirect('/view_doc' . '/' . $tableName);
    }



    public function view_doc($tableName)
    {
        $documents = DB::table($tableName)->get();

        // Loop through each document to get the doc_id and run the query
        foreach ($documents as $document) {
            $doc_id = $document->doc_id;

            // Retrieve the Master_doc_data based on the doc_id
            $master_doc_data = Master_doc_data::where("id", $doc_id)->first();

            // Merge $master_doc_data into $document
            if ($master_doc_data) {
                foreach ($master_doc_data->getAttributes() as $attribute => $value) {
                    $document->{$attribute} = $value;
                }
            }
        }

        return view('pages.view_doc', ['documents' => $documents, 'tableName' => $tableName]);
    }

    public function add_fields_first()
    {
        $doc_type = Master_doc_type::get();

        return view('pages.add_fields_first', ['doc_type' => $doc_type]);
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
            ->get(['column_name', 'data_type']);

        // Pass the column details to the view instead of the direct schema columns
        return view('pages.document_field', [
            'tableName' => $tableName,
            'columnDetails' => $columnDetails,
        ]);
    }

    public function edit_document($table, $id)
    {
        // dd(Auth::user()->type);
        $tableName = $table;
        $document = DB::table($tableName)->where('id', $id)->first();
        // if($document->status == 0 && Auth::user()->type==="admin"){
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }
        $field_types = DB::table($tableName)->where('id', 1)->first();
        // $document = DB::table($tableName)->where('id',$id)->first();
        // dd($document);
        return view('pages.edit_doc', ['columns' => $columns, 'field_types' => $field_types, 'document' => $document, 'table_name' => $tableName, 'id' => $id]);
        // }

    }
    public function edit_document_basic_detail($id)
    {
        // dd(Auth::user()->type);
        //here, id is the doc_id for the respective table
        $document = Master_doc_data::where('id', $id)->first();
        $states = State::all();
        // if($document->status == 0 && Auth::user()->type==="admin"){
        $sets = Set::all();
        // $document = DB::table($tableName)->where('id',$id)->first();
        // dd($document);
        return view('pages.edit_document_first', ['document' => $document, 'sets' => $sets, 'states' => $states]);
        // }

    }


    // public function update_document_status(Request $req){
    // $id = $req->id;
    // $tableName = $req->type;
    // if (Schema::hasTable($tableName)) {
    //     $columns = Schema::getColumnListing($tableName);
    // }

    // $document =  DB::table($tableName)->where('id', $id)->first();

    // $updateData = [];
    // $updateData['status'] = 1;

    // // Update the record in the database based on the $id (assuming an 'id' column is used for record identification)
    // DB::table($tableName)->where('id', $id)->update($updateData);

    // // Redirect back with a success message or to a different page
    // return redirect('/reviewer/view_doc'.'/'.$tableName)->with('success', 'Document updated successfully');
    // }



    public function review_doc1($table, $id)
    {
        $tableName = $table;
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }
        $field_types = DB::table($tableName)->where('id', 1)->first();
        $document = DB::table($tableName)->where('id', $id)->first();
        $get_document_master_data = Master_doc_data::where('id', $document->doc_id)->first();
        // dd($document);
        // dd( ['columns' => $columns, 'field_types' => $field_types, 'document' => $document, 'tableName' => $tableName, 'id' => $id,'master_data' => $get_document_master_data]);
        return view('pages.review_doc', ['columns' => $columns, 'field_types' => $field_types, 'document' => $document, 'tableName' => $tableName, 'id' => $id, 'master_data' => $get_document_master_data]);
    }

    public function review_doc($table, $id)
    {
        $tableName = $table;
        if (Schema::hasTable($tableName)) {
            $columnMetadata = Table_metadata::where('table_name', $tableName)
                ->get()
                ->keyBy('column_name'); // This will help you to easily find metadata by column name.
        }

        $document = DB::table($tableName)->where('id', $id)->first();
        $get_document_master_data = Master_doc_data::where('id', $document->doc_id)->first();



        // ...
        $set_ids = json_decode($get_document_master_data->set_id, true) ?? [];

        // Since SQL stores set_id as text, ensure the IDs are cast to string if they are not already
        $set_ids = array_map('strval', $set_ids);
        // dd($set_ids);
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

            $tableName = $entry->document_type_name;

            // Fetch data from the respective table using doc_id
            $data = DB::table($tableName)
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

        return view('pages.review_doc', [
            'columnMetadata' => $columnMetadata,
            'document' => $document,
            'tableName' => $tableName,
            'id' => $id,
            'master_data' => $get_document_master_data,
            'matchingData' => $matchingData,
        ]);
    }

    public function filterDocument(Request $request)
    {
        $documents = collect();

        $typeId = $request->input('type');
        // $numberOfPages = $request->input('number_of_pages');
        $state = $request->input('state');
        $district = $request->input('district');
        $village = $request->input('village');
        $locker_no = $request->input('locker_no');
        $old_locker_no = $request->input('old_locker_no');
        // $number_of_pages = $request->input('number_of_pages');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $number_of_pages_start = $request->input('number_of_pages_start');
        $number_of_pages_end = $request->input('number_of_pages_end');
        // dd($number_of_pages_start,$number_of_pages_end);
        // $area_range_start = $request->input('area_range_start');
        // $area_range_old = $request->input('area_range_old');

        // dd($end_date);
        // Flash input to the session
        $request->flash();
        $doc_types = Master_doc_type::get();

        // Get unique values from the state columns
        $states = Master_doc_data::pluck('state')
            ->merge(Master_doc_data::pluck('current_state'))
            ->merge(Master_doc_data::pluck('alternate_state'))
            ->unique()
            ->reject(function ($value) {
                return empty($value);
            }) // Reject empty values
            ->values();
        $districts = Master_doc_data::pluck('district')
            ->merge(Master_doc_data::pluck('current_district'))
            ->merge(Master_doc_data::pluck('alternate_district'))
            ->unique()
            ->reject(function ($value) {
                return empty($value);
            }) // Reject empty values
            ->values();
        $villages = Master_doc_data::pluck('village')
            ->merge(Master_doc_data::pluck('current_village'))
            ->merge(Master_doc_data::pluck('alternate_village'))
            ->unique()
            ->reject(function ($value) {
                return empty($value);
            }) // Reject empty values
            ->values();
        $filters = $request->only(['type', 'number_of_pages', 'state', 'district', 'village', 'locker_no', 'old_locker_no', 'start_date', 'end_date', 'number_of_pages_start', 'number_of_pages_end']);
        $filterSet = count(array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        }));

        if ($filterSet > 0) {
            if ($typeId == 'all') {
                $documents = Master_doc_data::paginate(15); // Adjust the number per page as needed
            } else {
                $documents = $this->filterdocumentService->filterDocuments($typeId, $state, $district, $village, $locker_no, $old_locker_no, $number_of_pages_start, $number_of_pages_end, $start_date, $end_date);
            }
        }
        // dd($documents);
        $data = [
            'documents' => $documents,
            'doc_type' => Master_doc_type::get(),
            'selected_type' => $typeId,
            'number_of_pages_start' => $number_of_pages_start,
            'number_of_pages_end' => $number_of_pages_end,
            'states' => $states,
            'districts' => $districts,
            'villages' => $villages,
        ];

        return view('pages.filter-document', $data);
    }

    public function dataSets()
    {
        return view('pages.data-sets');
    }
}
