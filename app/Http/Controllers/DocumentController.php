<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Validation\Validator;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use App\Services\DocumentTableService;
use App\Services\FilterDocumentService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Migrations\Migration;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment, Compliance, Set,State};


// use Illuminate\Database\Eloquent\Collection::paginate;
class DocumentController extends Controller
{
    protected $filterdocumentService;

    public function __construct(FilterDocumentService $filterdocumentService)
    {
        $this->filterdocumentService = $filterdocumentService;
    }

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
        $doc_type = Master_doc_type::orderBy('name')->get();
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
            'temp_id' => $req->temp_id,
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
            // 'document_sub_type' => $req->document_sub_type,
            'current_town' => $req->current_town,
            'town' => $req->town,
            'alternate_town' => $req->alternate_town,
            'old_locker_number' => $req->old_locker_number,
            'physically' => $req->physically,
            // 'status_description' => $req->status_description,
            // 'review' => $req->review,
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

        session()->flash('toastr', ['type' => 'success', 'message' => 'Document status updated successfully']);

        // Redirect back with a success message or to a different page
        return redirect('/review_doc/' . $tableName . '/' . $id)->with('success', 'Document status updated successfully');
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
            'temp_id' => $req->temp_id,
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
            // 'document_sub_type' => $req->document_sub_type,
            'current_town' => $req->current_town,
            'town' => $req->town,
            'alternate_town' => $req->alternate_town,
            'old_locker_number' => $req->old_locker_number,
            'physically' => $req->physically,
            // 'status_description' => $req->status_description,
            // 'review' => $req->review,
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

        // Pass the column details to the view instead of the direct schema columns
        return view('pages.document_field', [
            'tableName' => $tableName,
            'columnDetails' => $columnDetails,
        ]);
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
        // $old_locker_no = $request->input('old_locker_no');
        // $number_of_pages = $request->input('number_of_pages');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // $number_of_pages_start = $request->input('number_of_pages_start');
        // $number_of_pages_end = $request->input('number_of_pages_end');
        // dd($number_of_pages_start,$number_of_pages_end);
        $area_range_start = $request->input('area_range_start');
        $area_range_end = $request->input('area_range_end');
        $area_unit = $request->input('area_unit');

        // dd($end_date);
        // Flash input to the session
        $request->flash();

        // Get unique values from the state columns
        // $states = Master_doc_data::pluck('state')
        //     ->merge(Master_doc_data::pluck('current_state'))
        //     ->merge(Master_doc_data::pluck('alternate_state'))
        //     ->unique()
        //     ->sort()
        //     ->reject(function ($value) {
        //         return empty($value);
        //     }) // Reject empty values
        //     ->values();


        $states = Master_doc_data::pluck('current_state')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return empty($value);
            })
            ->values();



        // $districts = Master_doc_data::pluck('district')
        //     ->merge(Master_doc_data::pluck('current_district'))
        //     ->merge(Master_doc_data::pluck('alternate_district'))
        //     ->unique()
        //     ->sort()
        //     ->reject(function ($value) {
        //         return empty($value);
        //     }) // Reject empty values
        //     ->values();


        $districts = Master_doc_data::pluck('current_district')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return empty($value);
            })
            ->values();



        // $villages = Master_doc_data::pluck('village')
        //     ->merge(Master_doc_data::pluck('current_village'))
        //     ->merge(Master_doc_data::pluck('alternate_village'))
        //     ->unique()
        //     ->sort()
        //     ->reject(function ($value) {
        //         return empty($value);
        //     }) // Reject empty values
        //     ->values();


        $villages = Master_doc_data::pluck('current_village')
            ->flatMap(function ($item) {
                // Split the item by comma and trim spaces from each resulting piece
                return collect(explode(',', $item))->map(function ($i) {
                    return Str::of($i)->trim();
                });
            })
            ->unique()
            ->sort()
            ->reject(function ($value) {
                return empty($value);
            })
            ->values();



        $filters = $request->only(['type', 'number_of_pages', 'state', 'district', 'village', 'locker_no',  'start_date', 'end_date', 'area_range_start', 'area_range_end', 'area_unit']);
        $filterSet = count(array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        }));

        // if ($filterSet > 0) {
        //     if ($typeId == 'all') {
        //         $documents = Master_doc_data::paginate(15);
        //     } else {
        $documents = $this->filterdocumentService->filterDocuments($typeId, $state, $district, $village, $locker_no, $start_date, $end_date, $area_range_start, $area_range_end, $area_unit);
        // }
        // }
        // dd($documents);
        //    dd($area_unit);
        $data = [
            'documents' => $documents,
            'doc_type' => Master_doc_type::orderBy('name')->get(),
            'selected_type' => $typeId,
            // 'number_of_pages_start' => $number_of_pages_start,
            // 'number_of_pages_end' => $number_of_pages_end,
            'states' => $states,
            'districts' => $districts,
            'villages' => $villages,
            'area_unit' => $area_unit,
        ];

        return view('pages.filter-document', $data);
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
}
