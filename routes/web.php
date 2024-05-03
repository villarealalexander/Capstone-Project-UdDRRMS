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
    Route::get('/superadmin/activitylogs', [SuperadminController::class, 'activityLogs'])->name('superadmin.activitylogs');
    Route::get('/superadmin.index', [SuperadminController::class, 'index'])->name('superadmin');
    Route::put('/superadmin/{id}', [SuperadminController::class, 'update'])->name('superadmin.update');
    Route::post('/superadmin/create', [SuperadminController::class, 'store'])->name('superadmin.create');
    Route::get('superadmin/confirm-delete', [SuperadminController::class, 'confirmDelete'])->name('superadmin.confirm-delete');
    Route::post('/superadmin/destroy-multiple', [SuperadminController::class, 'destroyMultiple'])->name('superadmin.destroyMultiple');



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
    Route::get('/uploadfile', [EncoderController::class, 'create'])->name('uploadfile.create');
    Route::post('/uploadfile', [EncoderController::class, 'store'])->name('uploadfile.store');
    Route::delete('/delete-file/{id}', [EncoderController::class, 'deleteFile'])->name('deletefile');
    Route::get('/encoder/upload', [EncoderController::class, 'uploadfile'])->name('encoder.upload');
    Route::delete('/encoder/delete-folders', [EncoderController::class, 'deleteFolders'])->name('encoder.deleteFolders');
    Route::get('encoder/confirm-delete/{id}', [EncoderController::class, 'confirmDelete'])->name('encoder.confirm-delete');
    Route::post('/encoder/confirm-delete', [EncoderController::class, 'destroyMultiple'])->name('encoder.destroyMultiple');
    

    Route::resource('encoder', EncoderController::class)->middleware(['auth', 'verified']);
});


Route::middleware(['auth', 'CheckRole:viewer'])->group(function () {
    Route::get('/viewer/index', [ViewerController::class, 'index'])->name('viewer');
    
    Route::resource('viewer', ViewerController::class)->middleware(['auth', 'verified']);
});



Route::middleware(['auth', 'CheckRole:encoder,viewer,admin'])->group(function () {
    Route::get('/view-file/{id}', [EncoderController::class, 'viewFile'])->name('viewfile');
    Route::get('/student/{id}/files', [EncoderController::class, 'studentFiles'])->name('student.files');
});







