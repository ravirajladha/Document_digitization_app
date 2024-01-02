<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\User;
use App\Models\State;
use App\Models\Doc_type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Master_doc_data;

use App\Models\Master_doc_type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Validation\Validator;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use App\Services\DocumentTableService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Migrations\Migration;

class Admin extends Controller
{


    public function document_type()
    {
        $doc_types = Master_doc_type::get();

        return view('pages.document_type', ['doc_types' => $doc_types]);
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
            'name' => 'required|string|max:255', // Validation rules
        ]);

        // Create a new set
        $set = new Set;
        $set->name = $request->name;
        // Save the set to the database
        $set->save();
        return response()->json(['success' => 'Set added successfully.']);
        // Redirect or return response
        // return redirect('/set')->with('success', 'Set added successfully.');
    }


    public function getUpdatedSets()
{
    $sets = Set::get(); // Assuming Set is your model name
    return response()->json($sets);
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
        if ($result) {
            return redirect('/document_type')->with('success', 'Table and Model created successfully.');
        } else {
            return redirect('/document_type')->with('error', 'Table already exists.');
        }
    }





    public function add_document_field(Request $req)
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

            return redirect('/document_field' . '?type=' . $type)->with('success', 'Columns added successfully.');
            // return redirect()->route('document_field', ['table' => $type])->with('success', 'Columns added successfully, and the first row is updated/inserted.');

        } else {
            return redirect('/document_field' . '/' . $type)->with('error', 'Table does not exist.');
        }
    }


    public function add_document_first()
    {
        $doc_type = Master_doc_type::get();
        $sets = Set::get();
        $states = State::all();

        return view('pages.add_document_first', ['doc_type' => $doc_type,'sets' => $sets,'states' => $states]);
    }

    public function add_document_data(Request $req)
    {
        // dd($req->all());
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
            'set_id' =>$setsAsString,
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

        // Get the columns and document for the view
        $columns = Schema::getColumnListing($tableName);
        $document = DB::table($tableName)->where('id', 1)->first();
        $document_data = DB::table($tableName)->where('doc_id', $masterDocData->id)->first();

        // Redirect to the view with the new data
        // return view('pages.document-creation-continue', [
        //     'columns' => $columns,
        //     'document' => $document,
        //     'table_name' => $tableName,
        //     'id' => $document_data->id,
        //     'document_data' => $document_data
        // ]);

        return $this->documentCreationContinue(new Request([
            'columns' => $columns,
            'document' => $document,
            'table_name' => $tableName,
            'id' => $document_data->id,
            'document_data' => $document_data
        ]));

    }

    public function documentCreationContinue(Request $req)
    {
        // dd($req->all());
        // Extracting parameters from the request
        $tableName = $req->input('table_name');
        $id = $req->input('id');
        $document_data = $req->input('document_data');
// dd($tableName, $id, $document_data);
        // Retrieve columns and document based on the provided parameters
        $columns = Schema::getColumnListing($tableName);
        $document = DB::table($tableName)->where('id', 1)->first();
    $document_data = DB::table($tableName)->where('doc_id', $document_data->doc_id)->first();

// dd($document);
        // Render the view with all the necessary data
        return view('pages.document-creation-continue', [
            'columns' => $columns,
            'document' => $document,
            'table_name' => $tableName,
            'doc_id' => $id,
            'document_data' => $document_data,
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
            DB::table($tableName)->where('doc_id', $master_doc_id)->update($updateData);
        } else {
            // Insert a new record with the doc_id
            $updateData['doc_id'] = $master_doc_id; // Assuming 'doc_id' is the column name
            DB::table($tableName)->insert($updateData);
        }

        return redirect('/add_document_first');
    }

    public function update_document(Request $req)
    {
        $id = $req->id;
        $tableName = $req->type;
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }

        $document =  DB::table($tableName)->where('id', $id)->first();

        $updateData = [];
        $updateData['status'] = 1;

        // Update the record in the database based on the $id (assuming an 'id' column is used for record identification)
        DB::table($tableName)->where('id', $id)->update($updateData);
        // session()->flash('toastr', [
        //     'type' => 'success',
        //     'message' => 'Update successful!',
        //     'position' => 'top-center'
        // ]);
        // Redirect back with a success message or to a different page
        return redirect('/review_doc' . '/' . $tableName . '/' . $id)->with('success', 'Document updated successfully');
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
        'set_id' =>$setsAsString,
    ]);

    // Update the dynamic table record if needed
    if (Schema::hasTable($tableName)) {
        DB::table($tableName)->where('doc_id', $doc_id)->update([
            // Update the fields as necessary
            'document_name' => $req->name,
        ]);
    }

    // Get the columns and document for the view
    $columns = Schema::getColumnListing($tableName);
    // dd($tableName);
    $document = DB::table($tableName)->where('id', 1)->first();
// dd($document);
    // Redirect to the view with the updated data
    $document_data = DB::table($tableName)->where('doc_id', $doc_id)->first();
   


    return $this->documentCreationContinue(new Request([
        'columns' => $columns,
        'document' => $document,
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
        $document = DB::table($tableName)->get();
        $columns = Schema::getColumnListing($tableName);

        // return view('pages.view_doc',['document' => $document,'columns'=>$columns,'tableName' => $tableName]);
        return redirect('/view_doc' . '/' . $tableName);
    }




    public function view_doc($tableName)
    {
        $document = DB::table($tableName)->get();
        $columns = Schema::getColumnListing($tableName);

        return view('pages.view_doc', ['document' => $document, 'columns' => $columns, 'tableName' => $tableName]);
    }

    public function add_fields_first()
    {
        $doc_type = Master_doc_type::get();

        return view('pages.add_fields_first', ['doc_type' => $doc_type]);
    }
    public function document_field(Request $req, $table = null)
    {
        // dd($table);
        if ($table !== null) {
            $tableName = $table;
        }
        if ($req !== null) {
            $tableName = $req->type;
        }


        $columns = Schema::getColumnListing($tableName);
        $document = DB::table($tableName)->get();

        // dd($document);

        return view('pages.document_field', ['tableName' => $tableName, 'columns' => $columns, 'document' => $document,]);
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
    public function edit_document_basic_detail( $id)
    {
        // dd(Auth::user()->type);
        //here, id is the doc_id for the respective table
        $document = Master_doc_data::where('id', $id)->first();
        $states = State::all();
        // if($document->status == 0 && Auth::user()->type==="admin"){
      $sets = Set::all();
        // $document = DB::table($tableName)->where('id',$id)->first();
        // dd($document);
        return view('pages.edit_document_first', ['document' => $document,'sets'=> $sets,'states' => $states]);
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



    public function review_doc($table, $id)
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
        return view('pages.review_doc', ['columns' => $columns, 'field_types' => $field_types, 'document' => $document, 'tableName' => $tableName, 'id' => $id,'master_data' => $get_document_master_data]);
    }
}
