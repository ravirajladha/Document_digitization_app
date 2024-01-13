<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Reviewer;
use App\Http\Controllers\Receiver_process;
use App\Http\Controllers\Document;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::view('/error/500', 'error')->name('error');
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});
Route::get('/dashboard', function () {

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/verify-document/{token}', [Receiver_process::class, 'showPublicDocument'])->name('showPublicDocument');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // set view
    // @get
    Route::get('/set', [Admin::class, 'set'])->name('set');
    Route::get('/get-updated-sets', [Admin::class, 'getUpdatedSets'])->name('getUpdatedSets');
    Route::post('/add_set', [Admin::class, 'addSet'])->name('addSet');
    Route::post('/update-set', [Admin::class, 'updateSet'])->name('updateSet');
    //receiver type
    Route::get('/receiver-type', [Admin::class, 'receiverType'])->name('receiverType');
    Route::get('/get-updated-receiver-types', [Admin::class, 'getUpdatedReceiverTypes'])->name('getUpdatedReceiverTypes');
    Route::post('/add-receiver-type', [Admin::class, 'addReceiverType'])->name('addReceiverType');
    Route::post('/update-receiver-type', [Admin::class, 'updateReceiverType'])->name('updateReceiverType');

    // New routes for receivers
    Route::get('/receivers', [Admin::class, 'showReceivers'])->name('showReceivers');
    Route::post('/add-receiver', [Admin::class, 'addReceiver'])->name('addReceiver');
    Route::post('/update-receiver', [Admin::class, 'updateReceiver'])->name('updateReceiver');
    Route::get('/get-updated-receivers', [Admin::class, 'getUpdatedReceivers'])->name('getUpdatedReceivers');
    // New routes for assigning documents
    // Route::get('/get-receivers/{typeId}', [Receiver_process::class, 'getReceiversByType'])->name('getReceiversByType');
    Route::get('/get-documents/{typeId}', [Document::class, 'getDocumentsByType'])->name('getDocumentsByType');
    Route::get('/assign-documents', [Receiver_process::class, 'showAssignedDocument'])->name('showAssignedDocument');
// Add a parameter to your route for the receiver ID, assigned documents for one particular user
Route::get('/user-assign-documents/{receiver_id}', [Receiver_process::class, 'showUserAssignedDocument'])
     ->name('showUserAssignedDocument');

    //update the status of the assigned docu
    Route::post('/toggle-assigned-document-status/{id}', [Receiver_process::class, 'toggleStatus'])->name('toggleStatus');

    Route::get('/get-receivers/{typeId}', [Receiver_process::class, 'getReceiversByType'])->name('getReceiversByType');

    Route::post('/assign-documents-to-receiver', [Receiver_process::class, 'assignDocumentsToReceiver'])->name('assignDocumentsToReceiver');
    // Route::get('/document/{token}', [Receiver_process::class, 'showPublicDocument'])->name('document.show');

    Route::get('/document_type', [Admin::class, 'document_type'])->name('document_type');
    Route::post('/add_document_type', [Admin::class, 'addDocumentType'])->name('addDocumentType');
    Route::post('/add_document_field', [Admin::class, 'add_document_field'])->name('add_document_field');
    Route::get('/document_field/{table?}', [Admin::class, 'document_field'])->name('document_field');


    Route::get('/add_fields_first', [Admin::class, 'add_fields_first'])->name('add_fields_first');

    Route::post('/add_document', [Admin::class, 'add_document'])->name('add_document');
    Route::post('/add-basic-detail-to-master-doc-data', [Admin::class, 'addBasicDetailToMasterDocData'])->name('addBasicDetailToMasterDocData');
    Route::get('/add_document_first', [Admin::class, 'add_document_first'])->name('add_document_first');
    Route::post('/add-document-data', [Admin::class, 'add_document_data'])->name('add_document_data');
    Route::put('/update-first-document-data/{id}', [Admin::class, 'updateFirstDocumentData'])->name('updateFirstDocumentData');

    Route::get('/document-creation-continue', [Admin::class, 'documentCreationContinue'])->name('documentCreationContinue');


    Route::get('/view_doc_first', [Admin::class, 'view_doc_first'])->name('view_doc_first');
    Route::get('/view_doc_first_submit', [Admin::class, 'view_doc_first_submit'])->name('view_doc_first_submit');
    Route::get('/view_doc/{table}', [Admin::class, 'view_doc'])->name('view_doc');

    Route::get('/edit_document/{table}/{id}', [Admin::class, 'edit_document'])->name('edit_document');
    Route::get('/edit_document_basic_detail/{id}', [Admin::class, 'edit_document_basic_detail'])->name('edit_document_basic_detail');
    Route::post('/update_document', [Admin::class, 'update_document'])->name('update_document');
    Route::get('/review_doc/{table}/{id}', [Admin::class, 'review_doc'])->name('review_doc');
    Route::get('/filter-document', [Admin::class, 'filterDocument']);
    Route::get('/get-all-documents-type', [Admin::class, 'getAllDocumentsType']);

    //data sets
    Route::get('/data-sets', [Admin::class, 'dataSets']);

    // reviewer
    Route::get('/reviewer/index', [Reviewer::class, 'index']);
    Route::get('/reviewer/view_doc_first', [Reviewer::class, 'view_doc_first']);
    Route::get('/reviewer/view_doc_first_submit', [Reviewer::class, 'view_doc_first_submit']);
    Route::get('/reviewer/view_doc/{table}', [Reviewer::class, 'view_doc']);
    Route::post('/reviewer/update_document_status', [Reviewer::class, 'update_document_status']);
    Route::get('/reviewer/review_all/{table}/{id?}', [Reviewer::class, 'review_all']);
    Route::post('/reviewer/accept_and_next', [Reviewer::class, 'accept_and_next']);
});



require __DIR__ . '/auth.php';
