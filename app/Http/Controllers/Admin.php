<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\User;
use App\Models\Doc_type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class Admin extends Controller
{
    //
    // public function login_view(){
    //     return view('pages.login');
    // }

    // public function login(Request $req){
    //     $user = User::where('email', $req->email)->first();
    //     if($user && Hash::check($req->password, $user->password)) {
    //         if($user->type === 'admin'){
                
    //             $req->session()->put('admin', $user);
    //             Session::put('rexkod_user_type', $user->type);
    //             Session::put('rexkod_user_id', $user->id);
    //             Session::put('rexkod_user_name', $user->name);

    //             return redirect('/index');
    //         }
    //         elseif($user->type === 'reviewer'){

    //             $req->session()->put('reviewer', $user);
    //             Session::put('rexkod_user_type', $user->type);
    //             Session::put('rexkod_user_id', $user->id);
    //             Session::put('rexkod_user_name', $user->name);

    //             return redirect('/reviewer/index');
    //         }



    //     }else{
       
    //         return redirect('/')->with('error', 'Invalid Credentials');
    //     }

    //     return view('pages.index');
    // }

    // public function index(){

    //     return view('pages.index');
    // }

    public function document_type(){
        $doc_types = Doc_type::get();

        return view('pages.document_type',['doc_types' => $doc_types]);
    }
    public function set(){
        $data = Set::get();

        return view('pages.set',['data' => $data]);
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

        // Redirect or return response
        return redirect('/set')->with('success', 'Set added successfully.');
    }
public function add_document_type(Request $req)
{
    $type = strtolower(str_replace(' ', '_', $req->type));

     // Create a new Doc_type record with the type name
     $doc_type = new Doc_type();
     $doc_type->type = $type;
     $doc_type->save();

    // Check if the table with the given type name exists
    if (!Schema::hasTable($type)) {
        // Create the table with an 'id' column
        Schema::create($type, function (Blueprint $table) use ($doc_type) {
            
            $table->id();
            $table->text('document_name')->nullable();
            $table->text('doc_type')->nullable();
            $table->integer('status')->default(0);
            // You can add more columns here as needed
            // $table->timestamps(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            // dd($doc_type);
        });

        // Generate the model class with the first letter capital
        $modelClassName = ucfirst($type);

        // Generate the model class content
        $modelContent = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$modelClassName} extends Model\n{\n    protected \$table = '{$type}';\n}\n";

        // Save the model class to the app/Models directory
        file_put_contents(app_path("Models/{$modelClassName}.php"), $modelContent);

        // Run the migration to create the table
        // \Artisan::call('migrate');



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
        Schema::table($type, function (Blueprint $table) use ($type,$fields, $fieldType) {
            // Loop through the fields array and add columns with the specified field type
            foreach ($fields as $field) {
                $columns = Schema::getColumnListing($type);
                // dd(in_array($field, $columns));
                if(in_array($field, $columns)){
                    return redirect('/document_field'.'?type='.$type)->with('failed', 'Columns already exist.');
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
        $document = DB::table($type)->where('id',1)->first();
        if (!$document) {
            $insertData = array_merge(['id' => 1], $updateData); // Set 'id' to 1 or your desired ID
            DB::table($type)->insert($insertData);

        }else{
            DB::table($type)->where('id', 1)->update($updateData);
        }

        return redirect('/document_field'.'?type='.$type)->with('success', 'Columns added successfully.');
        // return redirect()->route('document_field', ['table' => $type])->with('success', 'Columns added successfully, and the first row is updated/inserted.');

    } else {
        return redirect('/document_field'.'/'.$type)->with('error', 'Table does not exist.');
    }
}


public function add_document_first(){
    $doc_type = Doc_type::get();

    return view('pages.add_document_first',['doc_type' => $doc_type]);
}

public function add_document_data(Request $req){
    $tableName = $req->type;
    if (Schema::hasTable($tableName)) {
        $columns = Schema::getColumnListing($tableName);
    }
    $document = DB::table($tableName)->where('id',1)->first();
    return view('pages.add-document-data',['columns' => $columns,'document'=>$document,'table_name' => $req->type]);
}

public function add_document(Request $req){
    $tableName = $req->type;
    if (Schema::hasTable($tableName)) {
        $columns = Schema::getColumnListing($tableName);
    }
    $updateData = ['doc_type' => $tableName];
        foreach ($columns as $column) {
            if (!($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'status' || $column == 'doc_type')){
                if (!empty($req->file($column))) {
                    $file_paths = [];
                foreach ($req->$column as $input) {
                    $extension = $input->getClientOriginalExtension();
                $filename = Str::random(4) . time() . '.' . $extension;
                $path = $input->move('uploads', $filename);
                $file_paths[] = 'uploads/'.$filename;
                }
                $updateData[$column] = implode(',',$file_paths);

                }else{
                    $updateData[$column] = $req->$column;
                }
            }
        }
        // dd($updateData);
        // $insertData = array_merge(['id' => 1], $updateData);
            DB::table($tableName)->insert($updateData);
    return redirect('/add_document_first');
}

public function change_password(){
    return view('pages.change_password');
}
// Auth::user()->type == "admin"
public function update_password(Request $req){
    $user = User::where('id', Auth::user()->id)->first();
    if($user && Hash::check($req->old_pw, $user->password)) {
        $user->password=Hash::make($req->new_pw);
        $user->save();
        return redirect('/change_password')->with('success', 'Password updated Successfully.');;

    }

    return redirect('/change_password')->with('success', 'Old Password is incorrect.');;
}
public function view_doc_first(){

    $doc_type = Doc_type::get();

    return view('pages.view_doc_first',['doc_type' => $doc_type]);
}

public function view_doc_first_submit(Request $req){
    $tableName = $req->type;
    $document = DB::table($tableName)->get();
    $columns = Schema::getColumnListing($tableName);

    // return view('pages.view_doc',['document' => $document,'columns'=>$columns,'tableName' => $tableName]);
    return redirect('/view_doc'.'/'.$tableName);
}




public function view_doc($tableName){
    $document = DB::table($tableName)->get();
    $columns = Schema::getColumnListing($tableName);

    return view('pages.view_doc',['document' => $document,'columns'=>$columns,'tableName' => $tableName]);
}

public function add_fields_first(){
    $doc_type = Doc_type::get();

    return view('pages.add_fields_first',['doc_type' => $doc_type]);
}
public function document_field(Request $req,$table = null){
    // dd($table);
    if($table !== null){
        $tableName = $table;
    }
    if($req !== null){
        $tableName = $req->type;
    }


    $columns = Schema::getColumnListing($tableName);
    $document = DB::table($tableName)->get();

    // dd($document);

    return view('pages.document_field',['tableName' => $tableName,'columns'=>$columns,'document' => $document,]);
}

public function edit_document($table, $id){
    // dd(Auth::user()->type);
    $tableName = $table;
    $document = DB::table($tableName)->where('id',$id)->first();
// if($document->status == 0 && Auth::user()->type==="admin"){
    if (Schema::hasTable($tableName)) {
        $columns = Schema::getColumnListing($tableName);
    }
    $field_types = DB::table($tableName)->where('id',1)->first();
    // $document = DB::table($tableName)->where('id',$id)->first();
    // dd($document);
    return view('pages.edit_doc',['columns' => $columns,'field_types'=>$field_types,'document'=>$document,'table_name' => $tableName,'id' => $id]);
// }

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
    return redirect('/review_doc'.'/'.$tableName.'/'.$id)->with('success', 'Document updated successfully');
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



public function review_doc($table,$id){
    $tableName = $table;
    if (Schema::hasTable($tableName)) {
        $columns = Schema::getColumnListing($tableName);
    }
    $field_types = DB::table($tableName)->where('id',1)->first();
    $document = DB::table($tableName)->where('id',$id)->first();
    // dd($document);
    return view('pages.review_doc',['columns' => $columns,'field_types'=>$field_types,'document'=>$document,'tableName' => $tableName,'id' => $id]);
}



}

