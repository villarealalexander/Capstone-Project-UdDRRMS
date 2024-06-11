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

        $filePath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' . $student->id . '/' . $file->file);

        if (file_exists($filePath)) {
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            $response = response()->download($filePath, $file->file, $headers);
            $response->send();
            ActivityLogService::log('Download', 'Downloaded a file from: ' . $student->name .' -> (Filename: ' . $file->file . ')');

            return $response;
        } else {
            return redirect()->back()->with('error', 'File not found.');
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
}
