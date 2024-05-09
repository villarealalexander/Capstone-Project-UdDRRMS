<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class AdminController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        $role = $user->role;
        $name = $user->name;

        $searchQuery = $request->input('query');
        $category = $request->input('category');
    
        $uploadedFilesQuery = Student::query();
    
        if ($searchQuery) {
            $uploadedFilesQuery
            ->where('name', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('batchyear', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('type_of_student', 'LIKE', '%' . $searchQuery . '%');
        }
    
        if ($category && in_array($category, ['Post Graduate', 'Masteral'])) {
            $uploadedFilesQuery->where('type_of_student', $category);
        }
    
        $students = $uploadedFilesQuery->with('uploadedFiles')->paginate(10);
    
        return view('admin.index', compact('students', 'searchQuery', 'category', 'role', 'name'));
    }

    public function downloadFile($id)
    {
        $file = UploadedFile::findOrFail($id);
        $student = $file->student;
    
        $filePath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' . $student->id . '/' . $file->file);
    

        if (file_exists($filePath)) {
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
    
            ActivityLogService::log('Download', 'Download a file from: ' . $student->name .'->'. '(Filename: ' . $file->file. ')');
    
            return response()->download($filePath, $file->file, $headers);
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }

        public function activityLogs()
    {
        $activityLogs = ActivityLog::whereHas('user', function ($query) {
            $query->whereNotIn('role', ['superadmin']);
        })->latest()->get();
        
        ActivityLogService::log('View', 'Viewed Activity Logs');

        return view('admin.activitylogs', compact('activityLogs'));
    }
}
