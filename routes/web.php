<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Reviewer;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// set view
// @get
    Route::get('/set', [Admin::class, 'set'])->name('set');
    Route::post('/add_set', [Admin::class, 'addSet'])->name('addSet');

    Route::get('/document_type', [Admin::class, 'document_type'])->name('document_type');
    Route::post('/add_document_type', [Admin::class, 'add_document_type'])->name('add_document_type');
    Route::get('/add_fields_first', [Admin::class, 'add_fields_first'])->name('add_fields_first');
    Route::get('/document_field/{table?}', [Admin::class, 'document_field'])->name('document_field');
    Route::post('/add_document_field', [Admin::class, 'add_document_field'])->name('add_document_field');
    Route::get('/add_document_first', [Admin::class, 'add_document_first'])->name('add_document_first');
    Route::post('/add-document-data', [Admin::class, 'add_document_data'])->name('add_document_data');
    Route::post('/add_document', [Admin::class, 'add_document'])->name('add_document');
    Route::get('/change_password', [Admin::class, 'change_password'])->name('change_password');
    Route::post('/update_password', [Admin::class, 'update_password'])->name('update_password');
    Route::get('/view_doc_first', [Admin::class, 'view_doc_first'])->name('view_doc_first');
    Route::get('/view_doc_first_submit', [Admin::class, 'view_doc_first_submit'])->name('view_doc_first_submit');
    Route::get('/view_doc/{table}', [Admin::class, 'view_doc'])->name('view_doc');
    Route::get('/edit_document/{table}/{id}', [Admin::class, 'edit_document'])->name('edit_document');
    Route::post('/update_document', [Admin::class, 'update_document'])->name('update_document');
    Route::get('/review_doc/{table}/{id}', [Admin::class, 'review_doc'])->name('review_doc');
    
    
    
    // reviewer
    Route::get('/reviewer/index', [Reviewer::class, 'index']);
    Route::get('/reviewer/view_doc_first', [Reviewer::class, 'view_doc_first']);
    Route::get('/reviewer/view_doc_first_submit', [Reviewer::class, 'view_doc_first_submit']);
    Route::get('/reviewer/view_doc/{table}', [Reviewer::class, 'view_doc']);
    Route::post('/reviewer/update_document_status', [Reviewer::class, 'update_document_status']);
    Route::get('/reviewer/review_all/{table}/{id?}', [Reviewer::class, 'review_all']);
    Route::post('/reviewer/accept_and_next', [Reviewer::class, 'accept_and_next']);
    

});

require __DIR__.'/auth.php';
