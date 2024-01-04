<?php 
// File: app/Services/DocumentTableService.php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Doc_type;
use App\Models\Master_doc_type;
use App\Models\Master_doc_data;

use Validator;
class DocumentService
{
    public function addDocument($requestData)
    {
        // Your logic for adding a document goes here
        // Return the result or data needed for further processing
    }

    public function updateDocument($id, $requestData)
    {
        // Your logic for updating a document goes here
        // Return the updated document or relevant data
    }
}

?>