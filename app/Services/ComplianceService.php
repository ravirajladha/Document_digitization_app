<?php 
// File: app/Services/BulkUploadService.php

namespace App\Services;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;
use App\Models\Property;
use App\Models\Doc_type;
use App\Models\Master_doc_type;
use App\Models\Table_metadata;
use App\Models\Master_doc_data;
use App\Services\DocumentTableService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;




class ComplianceService
{
    protected $complianceTableService;

    public function __construct()
    {
        $this->complianceTableService = new ComplianceService();
    }
    

    public function handleUpload($path)
    {
        
    }


}
?>