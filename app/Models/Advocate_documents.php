<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Advocate_documents extends Model
{
  
    protected $table = 'advocate_documents';

 
    protected $fillable = ['doc_id','advocate_id','created_by','case_name','case_status','start_date','end_date','court_name', 'urgency_level','notes','submission_deadline','status','court_case_location','plantiff_name','defendant_name', 'case_result'];

    public function advocate()
    {
        return $this->belongsTo(Advocate::class, 'advocate_id');
    }

    public function document()
    {
        return $this->belongsTo(Master_doc_data::class, 'doc_id');
    }
    
}
