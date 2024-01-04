<?php 
// File: app/Services/DocumentTableService.php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Doc_type;
use App\Models\Master_doc_type;
use App\Models\Master_doc_data;

use Validator;
class FilterDocumentService
{
    public function filterDocuments($typeId = null, $numberOfPages = null, $state = null, $district = null, $village = null,$locker_no=null,$old_locker_no=null,$number_of_pages=null): Collection
    {
        $query = Master_doc_data::query();

        if ($typeId) {
            $query->where('document_type', explode('|', $typeId)[0]);
        }

        if ($locker_no) {
            $query->where('locker_id', $locker_no);
        }
        if ($old_locker_no) {
            $query->where('old_locker_number', $old_locker_no);
        }
        if ($numberOfPages) {
            $query->where('number_of_page', '<=', $numberOfPages);
        }
    
        if ($state) {
            $query->where(function ($q) use ($state) {
                $q->where('state', $state)
                  ->orWhere('current_state', $state)
                  ->orWhere('alternate_state', $state);
            });
        }
        if ($district) {
            $query->where(function ($q) use ($district) {
                $q->where('district', $district)
                  ->orWhere('current_district', $district)
                  ->orWhere('alternate_district', $district);
            });
        }
        if ($village) {
            $query->where(function ($q) use ($village) {
                $q->where('village', $village)
                  ->orWhere('current_village', $village)
                  ->orWhere('alternate_village', $village);
            });
        }
        return $query->get();
    }
}

?>