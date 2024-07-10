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

        $studentsQuery = Student::query();

        if ($searchQuery) {
            $studentsQuery->where('name', 'LIKE', '%' . $searchQuery . '%')
                          ->orWhere('batchyear', 'LIKE', '%' . $searchQuery . '%')
                          ->orWhere('type_of_student', 'LIKE', '%' . $searchQuery . '%');
        }

        $sortField = $request->input('sort_field', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');

        if ($sortField === 'month_uploaded') {
            $monthsOrder = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            $studentsQuery->orderByRaw("FIELD(month_uploaded, '" . implode("', '", $monthsOrder) . "') " . $sortDirection);
        } else {
            $studentsQuery->orderBy($sortField, $sortDirection);
        }

        $students = $studentsQuery->get();

        $sortParams = [
            'field' => $sortField,
            'direction' => $sortDirection,
        ];

        ActivityLogService::log('View', 'Viewed the list of students.');

        return view('admin.index', compact('students', 'searchQuery', 'role', 'name', 'sortParams'));
    }

    public function downloadFile($id)
{
    $file = UploadedFile::findOrFail($id);
    $student = $file->student;

    $fileName = $file->file;
    $studentFolder = $student->name . '_' . $student->batchyear . '_' . $student->id;

    // Check if the file exists in public_path
    $filePath = public_path('uploads/' . $studentFolder . '/' . $fileName);

    if (file_exists($filePath)) {
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        $response = response()->download($filePath, $fileName, $headers);
        ActivityLogService::log('Download', 'Downloaded file: ' . $fileName . ' from local storage.');

        return $response;
    } else {
        // Initialize Firebase Cloud Storage
        $storage = app('firebase.storage');
        $bucket = $storage->getBucket();

        $firebaseFilePath = 'uploads/' . $studentFolder . '/' . $fileName;

        // Check if the file exists on Firebase
        $object = $bucket->object($firebaseFilePath);
        if (!$object->exists()) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Download the file from Firebase to local storage
        $object->downloadToFile($filePath);

        // Download the file from local storage
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        $response = response()->download($filePath, $fileName, $headers);
        ActivityLogService::log('Download', 'Downloaded file: ' . $fileName . ' from Firebase.');

        return $response;
    }
}

        public function activityLogs()
    {
        $activityLogs = ActivityLog::whereHas('user', function ($query) {
            $query->whereNotIn('role', ['superadmin']);
        })->latest()->get();
        
        $role = auth()->user()->role;
        $name = auth()->user()->name;

        ActivityLogService::log('View', 'Viewed Activity Logs');

        return view('admin.activitylogs', compact('activityLogs', 'role', 'name'));
    }

    public function checklist(Request $request)
    {
        ActivityLogService::log('View', 'Accessed the checklist page.');

        // Get the student ID from the request
        $studentId = $request->student_id;
        
        // Fetch the student and their uploaded files
        $student = Student::with('uploadedFiles')->findOrFail($studentId);
        $files = $student->uploadedFiles;

        return view('admin.checklist', compact('student', 'files'));
    }
}
