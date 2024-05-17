<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ViewerController;
use App\Http\Controllers\EncoderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperadminController;

Route::get('/', function () {
    return view('Login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'CheckRole:superadmin'])->group(function () {
    Route::get('/superadmin.index', [SuperadminController::class, 'index'])->name('superadmin');
    Route::put('/superadmin/{id}', [SuperadminController::class, 'update'])->name('superadmin.update');
    Route::post('/superadmin/create', [SuperadminController::class, 'store'])->name('superadmin.create');

    Route::get('superadmin/confirm-delete', [SuperadminController::class, 'confirmDelete'])->name('superadmin.confirm-delete');
    Route::post('/superadmin/destroy-multiple', [SuperadminController::class, 'destroyMultiple'])->name('superadmin.destroyMultiple');
    
    Route::get('/archives', [SuperadminController::class, 'archives'])->name('superadmin.archives');
    Route::get('/superadmin/archives', [SuperadminController::class, 'archives'])->name('superadmin.archives');
    Route::post('/superadmin/restore/{id}', [SuperadminController::class, 'restore'])->name('superadmin.restore');
    
    Route::get('/superadmin/activitylogs', [SuperadminController::class, 'activityLogs'])->name('superadmin.activitylogs');

    Route::resource('superadmin', SuperadminController::class)->middleware(['auth', 'verified']);
});

Route::middleware(['auth', 'CheckRole:admin'])->group(function () {
    Route::get('/admin/activitylogs', [AdminController::class, 'activityLogs'])->name('admin.activitylogs');
    Route::get('/admin/index', [AdminController::class, 'index'])->name('admin');
    Route::get('download-file/{id}', [AdminController::class, 'downloadFile'])->name('downloadfile');

    Route::resource('admin', AdminController::class)->middleware(['auth', 'verified']);
});


Route::middleware(['auth', 'CheckRole:encoder'])->group(function () {
    Route::get('/encoder/index', [EncoderController::class, 'index'])->name('encoder');

    Route::get('/encoder/upload', [EncoderController::class, 'uploadfile'])->name('encoder.upload');
    Route::post('/uploadfile', [EncoderController::class, 'store'])->name('uploadfile.store');

    Route::get('/encoder/add-file/{id}', [EncoderController::class, 'addFileForm'])->name('encoder.addFileForm');
    Route::post('/encoder/add-file/{id}', [EncoderController::class, 'addFileToStudent'])->name('encoder.addFile');
    
    Route::delete('/deletefile/{id}', [EncoderController::class, 'deleteFile'])->name('deletefile'); //PDF files
    Route::get('encoder/confirm-student-delete', [EncoderController::class, 'confirmStudentDelete'])->name('encoder.confirm-student-delete');
    Route::post('/encoder/destroy-multiple', [EncoderController::class, 'destroyMultiple'])->name('encoder.destroyMultiple');
    
    Route::get('/archives', [EncoderController::class, 'archives'])->name('encoder.archives');
    Route::get('/encoder/archives', [EncoderController::class, 'archives'])->name('encoder.archives');
    Route::put('/archive/{id}/restore', [EncoderController::class, 'restore'])->name('encoder.restore');
    
    Route::resource('encoder', EncoderController::class)->middleware(['auth', 'verified']);
});


Route::middleware(['auth', 'CheckRole:viewer'])->group(function () {
    Route::get('/viewer/index', [ViewerController::class, 'index'])->name('viewer');
    
    Route::resource('viewer', ViewerController::class)->middleware(['auth', 'verified']);
});



Route::middleware(['auth', 'CheckRole:encoder,viewer,admin'])->group(function () {
    Route::get('/viewfile/{id}', [EncoderController::class, 'viewFile'])->name('viewfile');
    Route::get('/student/{id}/files', [EncoderController::class, 'studentFiles'])->name('student.files');
});







