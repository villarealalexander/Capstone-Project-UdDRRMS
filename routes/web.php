<?php

use App\Http\Controllers\ArchiverController;
use App\Http\Controllers\HeadRegistrarController;
use App\Http\Controllers\MISController;
use App\Http\Controllers\RegistrarStaffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return view('Login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth', 'CheckRole:MIS'])->group(function () {
    Route::get('/MIS.index', [MISController::class, 'index'])->name('MIS');
    Route::put('/MIS/update/{id}', [MISController::class, 'update'])->name('MIS.update');
    Route::post('/MIS/create', [MISController::class, 'store'])->name('MIS.create');

    Route::get('MIS/confirm-delete', [MISController::class, 'confirmDelete'])->name('MIS.confirm-delete');
    Route::post('/MIS/destroy-multiple', [MISController::class, 'destroyMultiple'])->name('MIS.destroyMultiple');
    
    Route::get('/archives', [MISController::class, 'archives'])->name('MIS.archives');
    Route::get('/MIS/archives', [MISController::class, 'archives'])->name('MIS.archives');
    Route::post('/MIS/restore/{id}', [MISController::class, 'restore'])->name('MIS.restore');
    
    Route::get('/MIS/activitylogs', [MISController::class, 'activityLogs'])->name('MIS.activitylogs');

    Route::resource('MIS', MISController::class)->middleware(['auth', 'verified']);
});

Route::middleware(['auth', 'CheckRole:HeadRegistrar'])->group(function () {
    Route::get('/HeadRegistrar/activitylogs', [HeadRegistrarController::class, 'activityLogs'])->name('HeadRegistrar.activitylogs');
    Route::get('/HeadRegistrar/index', [HeadRegistrarController::class, 'index'])->name('HeadRegistrar');
    Route::get('download-file/{id}', [HeadRegistrarController::class, 'downloadFile'])->name('downloadfile');
    Route::get('/HeadRegistrar/checklist', [HeadRegistrarController::class, 'checklist'])->name('HeadRegistrar.checklist');
    Route::resource('HeadRegistrar', HeadRegistrarController::class)->middleware(['auth', 'verified']);
});


Route::middleware(['auth', 'CheckRole:Archiver'])->group(function () {
    Route::get('/Archiver/index', [ArchiverController::class, 'index'])->name('Archiver');

    Route::get('/Archiver/upload', [ArchiverController::class, 'uploadfile'])->name('Archiver.upload');
    Route::post('/uploadfile', [ArchiverController::class, 'store'])->name('uploadfile.store');
    Route::post('/student/{id}/uploadfile', [ArchiverController::class, 'addFileToStudent'])->name('student.addfile');
    Route::get('/Archiver/checklist', [ArchiverController::class, 'checklist'])->name('Archiver.checklist');
    Route::put('/Archiver/updatedescription/{file}', [ArchiverController::class, 'updateDescription'])->name('updatedescription');

    Route::delete('/deletefile/{id}', [ArchiverController::class, 'deleteFile'])->name('deletefile'); //PDF files
    Route::delete('/deletefilePermanently/{id}', [ArchiverController::class, 'deleteFilePermanently'])->name('deletefilePermanently');
    Route::delete('/Archiver/permanent-delete/{id}', [ArchiverController::class, 'permanentDeleteStudent'])->name('Archiver.permanentDeleteStudent');
    Route::get('Archiver/confirm-student-delete', [ArchiverController::class, 'confirmStudentDelete'])->name('Archiver.confirm-student-delete');
    Route::post('/Archiver/destroy-multiple', [ArchiverController::class, 'destroyMultiple'])->name('Archiver.destroyMultiple');
    
    Route::get('/archives', [ArchiverController::class, 'archives'])->name('Archiver.archives');
    Route::get('/Archiver/archives', [ArchiverController::class, 'archives'])->name('Archiver.archives');
    Route::put('/archive/{id}/restore', [ArchiverController::class, 'restore'])->name('Archiver.restore');
    Route::get('/Archiver/{id}/archived-files', [ArchiverController::class, 'showArchivedFiles'])->name('Archiver.archived-files');
    Route::put('/restorefile/{id}', [ArchiverController::class, 'restoreFile'])->name('restorefile');
    
    Route::resource('Archiver', ArchiverController::class)->middleware(['auth', 'verified']);
});


Route::middleware(['auth', 'CheckRole:RegistrarStaff'])->group(function () {
    Route::get('/RegistrarStaff/index', [RegistrarStaffController::class, 'index'])->name('RegistrarStaff');
    Route::get('/RegistrarStaff/checklist', [RegistrarStaffController::class, 'checklist'])->name('RegistrarStaff.checklist');
    Route::resource('RegistrarStaff', RegistrarStaffController::class)->middleware(['auth', 'verified']);
});



Route::middleware(['auth', 'CheckRole:Archiver,RegistrarStaff,HeadRegistrar'])->group(function () {
    Route::get('/viewfile/{id}', [ArchiverController::class, 'viewFile'])->name('viewfile');
    Route::get('/student/{id}/files', [ArchiverController::class, 'studentFiles'])->name('student.files');
});







