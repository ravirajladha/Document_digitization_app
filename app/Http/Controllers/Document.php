<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master_doc_data;
use App\Models\Master_doc_type;
use Illuminate\Support\Facades\Auth;

class Document extends Controller
{
    public function getDocumentsByType($typeId) {
        $documents = Master_doc_data::where('document_type', $typeId)->get();
        return response()->json(['documents' => $documents]);
    }
    
  
}
