<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Doc_type;
use App\Models\User;


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reviewer extends Controller
{
    //

    public function index(){

        return view('reviewer.index');
    }

    public function view_doc_first(){

        $doc_type = Doc_type::get();

        return view('reviewer.view_doc_first',['doc_type' => $doc_type]);
    }

    public function view_doc_first_submit(Request $req)
    {
        dd("sdfsd");
        $tableName = $req->type;
    
        // Check if the table exists in the database
        if (!$tableName) {
            return redirect()->back()->with('error', 'Table does not exist.');
        }
        if (!Schema::hasTable($tableName)) {
            return redirect()->back()->with('error', 'Table does not exist.');
        }
    
        $document = DB::table($tableName)->get();
    
        // Check if the table has any data
        if ($document->isEmpty()) {
            return redirect()->back()->with('error', 'No data available in the selected table.');
        }
    
        $columns = Schema::getColumnListing($tableName);
    
        // If everything is fine, redirect to the review page
        return redirect('/reviewer/view_doc' . '/' . $tableName);
    }
    
    public function view_doc($tableName){
        $document = DB::table($tableName)->get();
        $columns = Schema::getColumnListing($tableName);

        return view('reviewer.view_doc',['document' => $document,'columns'=>$columns,'tableName' => $tableName]);
    }


public function update_document_status(Request $req){
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

    // Redirect back with a success message or to a different page
    return redirect('/reviewer/view_doc'.'/'.$tableName)->with('success', 'Document updated successfully');
}



public function review_all($table,$id =null ){
    $tableName = $table;
    if (Schema::hasTable($tableName)) {
        $columns = Schema::getColumnListing($tableName);
    }
    $field_types = DB::table($tableName)->where('id',1)->first();
    if($id === null){
        $document = DB::table($tableName)->where('status',0)->where('id', '!=', 1)->first();
    }else{
        $document = DB::table($tableName)->where('id', '>', $id)
        ->where('status', 0)
        ->first();
        // $document = DB::table($tableName)->where('id',$id)->first();
    }
    // dd($document);
    if(!$document){
        return redirect('/reviewer/view_doc'.'/'.$tableName)->with('success', 'All Documents are updated successfully.');
    }else{
        return view('reviewer.review_all',['columns' => $columns,'field_types'=>$field_types,'document'=>$document,'tableName' => $tableName]);
    }
}

public function accept_and_next(Request $req){
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

    // Redirect back with a success message or to a different page
    return redirect('/reviewer/review_all'.'/'.$tableName.'/'.$id)->with('success', 'Document updated successfully');
}


}
