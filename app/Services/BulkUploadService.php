<?php 
// File: app/Services/BulkUploadService.php

namespace App\Services;

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
use App\Models\Master_doc_data;
use App\Services\DocumentTableService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class BulkUploadService
{
    protected $documentTypeService;

    public function __construct()
    {
        $this->documentTableService = new DocumentTableService();
    }
    

    public function handleUpload($path)
    {
        $stats = [
            'total' => 0,
            'inserted' => 0,
            'updated' => 0,
            'not_used' => 0,  // Assuming 'not_used' means skipped due to validation or other reasons
        ];
      // Generate a unique batch ID for this upload, used for insertIntoDynamicTables, to update only those details of the excel which was of this batch
      $batchId = (string) Str::uuid();
        // Open the file
        $handle = fopen($path, 'r');
    
        // Create a LazyCollection to process the CSV
        LazyCollection::make(function () use ($handle) {
            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
        })
        ->skip(1) // Skip the header of the CSV file.
        ->chunk(10000) // Process in chunks of 1000 rows.
        ->each(function ($chunk) use (&$stats ,$batchId) {
            // Prepare an array to hold all rows for batch insertion.
            $rowsToInsert = [];
    
            foreach ($chunk as $row) {
              
                // Apply your validation and logic here.
                if($row[6]){
                
                $processedRow = $this->processRow($row, $batchId);
            }else{
                $stats['not_used']++;
            }
                if ($processedRow) {
                    $rowsToInsert[] = $processedRow;
                }
            }
    
            // Perform the batch insert. very effiicient
            // dd($rowsToInsert);
            $masterDocData =  Master_doc_data::upsert($rowsToInsert, ['temp_id']);

            $stats['total'] += count($rowsToInsert);
            // Assuming all operations are insertions for simplicity
            // $stats['inserted'] += count($rowsToInsert);
         
        });
    
        // Close the file handle
        fclose($handle);
        //the below function calls and find the data which is not added in the subsequent table, and refresh the sub table with the data
        
        $dynamicStats =  $this->insertIntoDynamicTables($batchId);
            // Combine the stats
            // dd($stats, $dynamicStats);
    $stats['inserted'] += $dynamicStats['inserted'];
    // $stats['updated'] += $dynamicStats['updated'];
    $stats['updated'] += $dynamicStats['updated'];
    // dd($stats);
    //$stats results are coming wrong, need to work on that.
    return $stats;
    }


    protected function insertIntoDynamicTables($batchId) {
        $dynamicStats = [
            'inserted' => 0,
            'updated' => 0,
            'not_used'=> 0,
        ];
    
        // Retrieve all distinct table names from the `master_doc_data` table for the given batch_id
        $tableNames = Master_doc_data::where('batch_id', $batchId)
                                      ->distinct()
                                      ->pluck('document_type_name');
    
        foreach ($tableNames as $tableName) {
            if (Schema::hasTable($tableName)) {
                // Get the relevant records from `master_doc_data` for this table and batch_id
                $records = Master_doc_data::where('document_type_name', $tableName)
                                          ->where('batch_id', $batchId)
                                          ->get();
               
                foreach ($records as $record) {
                    // Check if a record with the same doc_id exists in the dynamic table
                    $existingRecord = DB::table($tableName)->where('doc_id', $record->id)->first();
    
                    if ($existingRecord) {
                        // The record exists, so we'll increment the 'updated' count.
                        // Update the existing record with new data if necessary
                        DB::table($tableName)->where('doc_id', $record->id)->update([
                            // ... include fields that should be updated
                            'pdf_file_path' => "uploads/documents/".$record->temp_id.".pdf",
                        ]);
                        $dynamicStats['updated']++;
                    } else {
                        // The record does not exist, it's a new insertion.
                        DB::table($tableName)->insert([
                            'doc_id' => $record->id,
                            'doc_type' => $tableName,
                            'document_name' => $record->name,
                            'pdf_file_path' => "uploads/documents/".$record->temp_id.".pdf",
                            // ... include other fields that should be inserted
                        ]);
                        $dynamicStats['inserted']++;
                    }
                }
            } else {
                // Optionally handle the case where the table does not exist
                $dynamicStats['not_used']++;
            }
        }
    
        return $dynamicStats;
    }
    

    protected function processRow($row,$batchId)
    {
        // $dateFromCsv = $row[19];
        // $formattedDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dateFromCsv)->format('Y-m-d');

        // Transform the row to your needs.
        $documentType = $this->documentTableService->createDocumentType($row[6]);
        return [
            'temp_id' => $row[1],
            'name' => $row[2],
            'location' => $row[3],
            'locker_id' =>  isset($row[4]) ? (int) $row[4] : null,
            'number_of_page' => isset($row[5]) ? (int) $row[5] : null,
            'document_type' => $documentType->id,
            'document_type_name' => $row[6],
            'current_state' => $row[7],
            'state' => $row[8],
            'alternate_state' => $row[9],
            'current_district' => $row[10],
            'district' => $row[11],
            'alternate_district' => $row[12],
            'current_taluk' => $row[13],
            'taluk' => $row[14],
            'alternate_taluk' => $row[15],
            'current_village' => $row[16],
            'village' => $row[17],
            'alternate_village' => $row[18],
            // 'issued_date' => $formattedDate,
            'document_sub_type' => $row[20],
            'current_town' => $row[21],
            'town' => $row[22],
            'alternate_town' => $row[23],
            'old_locker_number' => $row[24],
            'physically' => $row[26],
            'bulk_uploaded' => 1,
            'created_by' => Auth::user()->id,
            'batch_id' => $batchId,
        
            // ... add more fields as necessary
        ];
    }

  

}
?>