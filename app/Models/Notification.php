<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    // protected $fillable = ['type', 'message', 'compliance_id', 'created_by','user_id','document_assignment_id','is_read'];
    protected $guarded = ['id'];


    public function compliance()
    {
        return $this->belongsTo(Compliance::class, 'compliance_id');
    }

    // public function document()
    // {
    //     return $this->belongsTo(Master_doc_data::class, 'doc_id');
    // }
}
