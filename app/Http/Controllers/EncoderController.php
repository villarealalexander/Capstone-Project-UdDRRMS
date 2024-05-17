<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EncoderController extends Controller
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
        $monthsOrder = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $studentsQuery->orderByRaw("FIELD(month_uploaded, '" . implode("', '", $monthsOrder) . "') " . $sortDirection);
    } else {
        $studentsQuery->orderBy($sortField, $sortDirection);
    }

    // Retrieve all matching records without pagination
    $students = $studentsQuery->get();

    // Pass sorting parameters to the view
    $sortParams = [
        'field' => $sortField,
        'direction' => $sortDirection,
    ];

    // Return view without pagination
    return view('encoder.index', compact('students', 'searchQuery', 'role', 'name', 'sortParams'));
}

    public function uploadfile()
    {
        return view('encoder.upload');
    }

    public function store(Request $request)
{
    // Dynamic validation rules
    $messages = [
        'file.required' => 'You must upload at least one PDF file.',
        'file.*.mimes' => 'Only PDF files are allowed.',
        'file.*.max' => 'Each file may not be greater than 10MB.',
    ];
    $validator = Validator::make($request->all(), [
        'Name' => 'required|string|max:255',
        'BatchYear' => 'required|numeric',
        'type_of_student' => 'required|string|in:Undergraduate,Post Graduate',
        'undergradCourses' => ['required_if:type_of_student,Undergraduate', Rule::requiredIf(function () use ($request) {
            return $request->input('type_of_student') === 'Undergraduate';
        })],
        'postGradDegrees' => 'required_if:type_of_student,Post Graduate',
        'mastersCourses' => 'required_if:postGradDegrees,Masters',
        'doctorateCourses' => 'required_if:postGradDegrees,Doctorate',
        'major' => 'required_if:undergradCourses,BS IN BUSINESS ADMINISTRATION|required_if:undergradCourses,BACHELOR OF SECONDARY EDUCATION',
        'file' => 'required|array|min:1',
        'file.*' => 'required|file|max:10240|mimes:pdf',
    ], $messages);


    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Process course and major based on type of student
    $course = null;
    $major = null;

    switch ($request->input('type_of_student')) {
        case 'Undergraduate':
            $course = $request->input('undergradCourses');
            if ($course === 'BS IN BUSINESS ADMINISTRATION' || $course === 'BACHELOR OF SECONDARY EDUCATION') {
                $major = $request->input('major');
            }
            break;
        case 'Post Graduate':
            $degree = $request->input('postGradDegrees');
            $course = $degree === 'Masters' ? $request->input('mastersCourses') : $request->input('doctorateCourses');
            if ($degree !== 'Masters' && $degree !== 'Doctorate') {
                $major = $request->input('major');
            }
            break;
    }

    // Get current month name (e.g., January, February, etc.)
    $currentMonth = date('F');

    // Update or create a student record
    $student = Student::updateOrCreate([
        'name' => $request->input('Name'),
        'batchyear' => $request->input('BatchYear'),
        'type_of_student' => $request->input('type_of_student'),
        'course' => $course,
        'major' => $major,
        'month_uploaded' => $currentMonth,
    ]);

    // Handle file uploads
    if ($request->hasFile('file')) {
        foreach ($request->file('file') as $file) {
            $fileName = $file->getClientOriginalName();

        $studentFolderPath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' .  $student->id . '/');

        if (!file_exists($studentFolderPath)) {
            mkdir($studentFolderPath, 0755, true);
        }

        $file->move($studentFolderPath, $fileName);

        UploadedFile::create([
            'student_id' => $student->id,
            'file' => $fileName,
        ]);
        }
    }

    return redirect()->route('encoder.index')->with('success', 'Student information and files uploaded successfully.');
}

public function addFileToStudent(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'file.*' => 'required|file|max:10240|mimes:pdf',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $student = Student::findOrFail($id);

    if ($request->hasFile('file')) {
        foreach ($request->file('file') as $file) {
            $fileName = $file->getClientOriginalName();

        $studentFolderPath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' .  $student->id . '/');

        if (!file_exists($studentFolderPath)) {
            mkdir($studentFolderPath, 0755, true);
        }

        $file->move($studentFolderPath, $fileName);

        UploadedFile::create([
            'student_id' => $student->id,
            'file' => $fileName,
        ]);
    }
    }
    return redirect()->route('student.files', $id)->with('success', 'File uploaded successfully.');
}


public function show ($id)
{
    $student = Student::findOrFail($id);

    return view('encoder.show', compact('student'));
}

public function destroy($id)
{
    $student = Student::findOrFail($id);
    $student->delete(); 

    ActivityLogService::log('Delete', 'Soft deleted a user: ' . $student->name . ' (Batch Year: ' . $student->batchyear .')'  . ', ' . ' (Type of Student: '. $student->type_of_student . ')' . ', ' . ' (Course: '. $student->course . ')' . ', ' . ' (Major: '. $student->major . ')');

    return redirect()->route('encoder.index')->with('success', 'Student deleted successfully.');
}

public function studentFiles($id)
{
    $student = Student::findOrFail($id);
    $user = auth()->user();

    if ($user && in_array($user->role, ['encoder', 'viewer', 'admin'])) {
        $files = UploadedFile::withTrashed()->where('student_id', $student->id)->get();

        return view('encoder.student_files', compact('files', 'student'));
    } else {
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}

public function viewFile($id)
{
    $file = UploadedFile::findOrFail($id);
    $filePath = public_path('uploads/' . $file->student->name . '_' . $file->student->batchyear . '_' . $file->student->id . '/' . $file->file);

    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'application/pdf']);
    } else {
        return back()->with('error', 'File not found.');
    }
}

public function deleteFile($id)
{
    $file = UploadedFile::findOrFail($id);

    $filePath = public_path('uploads/' . $file->student->name . '_' . $file->student->batchyear . '_' . $file->student->id . '/' . $file->file);

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Soft delete the file record
    // $file->delete();

    $file->forceDelete();

    return redirect()->back()->with('success', 'File permanently deleted successfully.');
}

public function confirmStudentDelete(Request $request)
{
    $selectedStudentIds = $request->input('selected_students', []);

    if (empty($selectedStudentIds)) {
        return redirect()->route('encoder.index')->with('error', 'No folders selected for deletion.');
    }

    return view('encoder.confirm-student-delete', compact('selectedStudentIds'));
}

public function destroyMultiple(Request $request)
{
    $studentIds = $request->input('studentsToDelete', []);
    $encoderPassword = $request->input('encoder_password');

    $validatedData = $request->validate([
        'encoder_password' => 'required|string',
    ]);

    // Verify encoder password
    if (!Hash::check($encoderPassword, auth()->user()->password)) {
        return redirect()->back()->with('error', 'Incorrect encoder password. Please try again.');
    }

    foreach ($studentIds as $studentId) {
        $student = Student::withTrashed()->findOrFail($studentId);

        $student->uploadedFiles()->delete();
        $student->delete(); 
    }

    // Log activity
    ActivityLogService::log('Delete folders', 'Deleted selected folders: ' . implode(', ', $studentIds));

    return redirect()->route('encoder.archives')->with('success', 'Selected folders and associated files soft deleted successfully.');
}


public function restore($id)
{
    $restoredStudent = Student::onlyTrashed()->findOrFail($id);

    $restoredStudent->restore();

    $restoredFiles = UploadedFile::onlyTrashed()->where('student_id', $restoredStudent->id)->get();

    foreach ($restoredFiles as $file) {
        $file->restore(); 
    }

    ActivityLogService::log('Restore student', 'Restored student: ' . $restoredStudent->name);

    return redirect()->route('encoder.archives')->with('success', 'Student and associated files restored successfully.');
}

public function archives()
{

    $archivedStudents = Student::onlyTrashed()->get();

    ActivityLogService::log('View', 'Accessed archived students.');

    return view('encoder.archives', compact('archivedStudents'));
}
    
}