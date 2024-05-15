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

    // Sorting logic based on 'month_uploaded' attribute
    $sortField = $request->input('sort_field', 'name'); // Default sort by 'name'
    $sortDirection = $request->input('sort_direction', 'asc'); // Default ascending order

    // Custom sorting logic for 'month_uploaded'
    if ($sortField === 'month_uploaded') {
        // Define an array with month names in the desired order
        $monthsOrder = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Sort by the index of month names array based on the current sorting direction
        $studentsQuery->orderByRaw("FIELD(month_uploaded, '" . implode("', '", $monthsOrder) . "') " . $sortDirection);
    } else {
        // Default sorting for other fields
        $studentsQuery->orderBy($sortField, $sortDirection);
    }

    $students = $studentsQuery->paginate(5);

    // Pass sorting parameters to the view
    $sortParams = [
        'field' => $sortField,
        'direction' => $sortDirection,
    ];

    return view('admin.index', compact('students', 'searchQuery', 'role', 'name', 'sortParams'));
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
