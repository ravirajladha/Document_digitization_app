<?php

use App\Http\Controllers\Document;

use App\Http\Controllers\{NotificationController,ReceiverController,DocumentController,SetController,UserController,ComplianceController,Dashboard,Receiver_process,ProfileController};
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
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/verify-document/{token}', [Receiver_process::class, 'showPublicDocument'])->name('showPublicDocument');
Route::get('/otp/{token}', [Receiver_process::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-document/{token}', [Receiver_process::class, 'verifyOtp'])->name('otp.verify');
Route::post('/send-otp', [Receiver_process::class, 'sendOTP'])->name('otp.send');


Route::middleware(['auth', 'verified', 'checkuserpermission'])->group(function () {
    Route::get('/dashboard', [Dashboard::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //error page
    Route::view('/error-page-403', 'pages.error-403')->name('error-403');

    // set view

    // Route::get('/set', [DocumentController::class, 'set'])->name('set');
    Route::get('/set', [SetController::class, 'viewSet'])->name('sets.view');
    Route::get('/get-updated-sets', [SetController::class, 'viewUpdatedSets'])->name('sets.viewUpdated');
    Route::post('/add_set', [SetController::class, 'addSet'])->name('sets.add');
    Route::post('/update-set', [SetController::class, 'updateSet'])->name('sets.update');

    //receiver type

    Route::get('/receiver-type', [ReceiverController::class, 'receiverType'])->name('receiverTypes.view');
    //ajax response (read)
    Route::get('/get-updated-receiver-types', [ReceiverController::class, 'getUpdatedReceiverTypes'])->name('receiverTypes.updated');
    Route::post('/add-receiver-type', [ReceiverController::class, 'addReceiverType'])->name('receiverTypes.add');
    Route::post('/update-receiver-type', [ReceiverController::class, 'updateReceiverType'])->name('receiverTypes.update');

    // receivers
    Route::get('/receivers', [ReceiverController::class, 'showReceivers'])
        ->name('receivers.index');
    Route::post('/add-receiver', [ReceiverController::class, 'addReceiver'])
        ->name('receivers.store');
    Route::post('/update-receiver', [ReceiverController::class, 'updateReceiver'])
        ->name('receivers.update');
    Route::get('/get-receivers/{typeId}', [Receiver_process::class, 'getReceiversByType'])
        ->name('receivers.byType');
    Route::get('/get-updated-receivers', [ReceiverController::class, 'getUpdatedReceivers'])
        ->name('receivers.updated');


    // assigning documents


    Route::get('/assign-documents', [Receiver_process::class, 'showAssignedDocument'])
        ->name('documents.assigned.show');
    Route::get('/user-assign-documents/{receiver_id}', [Receiver_process::class, 'showUserAssignedDocument'])
        ->name('user.documents.assigned.show');
    Route::post('/toggle-assigned-document-status/{id}', [Receiver_process::class, 'toggleStatus'])
        ->name('documents.assigned.toggleStatus');
    Route::post('/assign-documents-to-receiver', [Receiver_process::class, 'assignDocumentsToReceiver'])
        ->name('documents.assign.toReceiver');

    //document type
    Route::get('/document_type', [DocumentController::class, 'document_type'])->name('document_types.index');
    Route::post('/add_document_type', [DocumentController::class, 'addDocumentType'])->name('document_types.store');

    // Route::get('/get-all-documents-type', [DocumentController::class, 'getAllDocumentsType'])
    // ->name('documents.types.all');
    //documents field (dynamic column)
    // Route::get('/add_fields_first', [DocumentController::class, 'add_fields_first'])->name('fields.create_first_step');
    Route::post('/add_document_field', [DocumentController::class, 'add_document_field'])->name('document_fields.store');
    Route::get('/document_field/{table?}', [DocumentController::class, 'document_field'])->name('document_fields.view');

    //documents
    Route::post('/add_document', [DocumentController::class, 'add_document'])
        ->name('documents.store');

    // Route::post('/add-basic-detail-to-master-doc-data', [DocumentController::class, 'addBasicDetailToMasterDocData'])
    //     ->name('master_documents.addBasicDetail');
    Route::get('/add_document_first', [DocumentController::class, 'add_document_first'])
        ->name('documents.add_document_first');

    // Route::get('/add_document_first', [DocumentController::class, 'add_document_first'])
    //     ->middleware('checkuserpermission:add_document_first') 
    //     ->name('add_document_first');


    Route::get('/review_doc/{table}/{id}', [DocumentController::class, 'review_doc'])
        ->name('documents.review');
    Route::post('/add-document-data', [DocumentController::class, 'add_document_data'])
        ->name('documents.data.add');
    Route::put('/update-first-document-data/{id}', [DocumentController::class, 'updateFirstDocumentData'])
        ->name('documents.data.first.update');
    Route::get('/document-creation-continue', [DocumentController::class, 'documentCreationContinue'])
        ->name('documents.creation.continue');

  

    // Route::get('/edit_document/{table}/{id}', [DocumentController::class, 'edit_document'])
    //     ->name('documents.edit');
    Route::get('/edit_document_basic_detail/{id}', [DocumentController::class, 'edit_document_basic_detail'])
        ->name('documents.basic_detail.edit');
    Route::post('/update_document', [DocumentController::class, 'update_document'])
        ->name('documents.updateStatus');

    //view documents
    Route::get('/filter-document', [DocumentController::class, 'filterDocument'])
        ->name('documents.filter');
    // Route::get('/view_doc_first', [DocumentController::class, 'view_doc_first'])
    // ->name('documents.view.first');
    Route::get('/documents-for-set/{setId}', [SetController::class, 'viewDocumentsForSet'])->name('sets.viewDocuments');

    //ajax call to get the documen from doc_type
    Route::get('/get-documents/{typeId}', [Document::class, 'getDocumentsByType'])
        ->name('documents.getByType');

    Route::get('/api/fetch/{type}/{id}', [Document::class, 'fetchData']);


    //data sets
    Route::get('/data-sets', [DocumentController::class, 'configure'])->name('configure');

    //bulk upload documents 
    Route::get('/bulk-upload-master-data', [Document::class, 'bulkUploadMasterData'])
        ->name('master_data.bulk_upload');
    Route::post('/bulk-upload-master-document-data', [Document::class, 'bulkUploadMasterDocumentData'])
        ->name('master_documents.bulk_upload');
    Route::post('/bulk-upload-child-document-data', [Document::class, 'bulkUploadChildDocumentData'])
        ->name('child_documents.bulk_upload');

    //compliance routes
    Route::get('/compliances', [ComplianceController::class, 'showCompliances'])
        ->name('compliances.index');
    Route::post('/create-compliances', [ComplianceController::class, 'store'])
        ->name('compliances.store');
    Route::post('/status-change-compliance/{id}/{action}', [ComplianceController::class, 'statusChangeCompliance'])
        ->name('compliances.status_change');
        //to change the status of is recurring of the COompliances
        Route::post('/toggle-compliances-is-recurring/{id}', [ComplianceController::class, 'toggleIsRecurring'])
        ->name('compliances.isRecurring.toggle');

    //notifications
    Route::get('/notifications', [NotificationController::class, 'showNotifications'])
        ->name('notifications.index');

    //user
    //useres//subadmin
    Route::get('/users', [UserController::class, 'showUsers'])
        ->name('users.index');



    Route::post('/register-user', [UserController::class, 'store'])
        ->name('users.store');
    // Display the edit form

    Route::get('/users/{user}/edit', [UserController::class, 'showUsers'])->name('users.edit');

    // Process the update form submission
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');




    //         Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    // Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');




});



require __DIR__ . '/auth.php';
