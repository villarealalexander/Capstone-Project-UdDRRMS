<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
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
        $category = $request->input('category');

        $studentsQuery = Student::query();

        if ($searchQuery) {
            $studentsQuery->where('name', 'LIKE', '%' . $searchQuery . '%')
                          ->orWhere('batchyear', 'LIKE', '%' . $searchQuery . '%')
                          ->orWhere('type_of_student', 'LIKE', '%' . $searchQuery . '%');
        }

        if ($category && in_array($category, ['Post Graduate', 'Masteral'])) {
            $studentsQuery->where('type_of_student', $category);
        }

        $students = $studentsQuery->with('uploadedFiles')->paginate(10);

        return view('encoder.index', compact('students', 'searchQuery', 'category', 'role', 'name'));
    }

    public function uploadfile()
    {
        return view('encoder.upload');
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'Name' => 'required|string',
        'BatchYear' => 'required|numeric',
        'type_of_student' => 'required|string|in:Undergraduate,Post Graduate',
        'file.*' => 'required|file|max:10240|mimes:pdf',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $course = null;
    $major = null;

    if ($request->input('type_of_student') === 'Undergraduate') {
        $course = $request->input('undergradCourses');

        if ($course) {
            $major = $request->input('major');
        }
    } elseif ($request->input('type_of_student') === 'Post Graduate') {
        $course = $request->input('postGradDegrees');
        if ($course === 'Masters') {
            $course = $request->input('mastersCourses');
        } elseif ($course === 'Doctorate') {
            $course = $request->input('doctorateCourses');
        }
        if ($course) {
            $major = $request->input('major');
        }
    }

    $student = Student::updateOrCreate([
        'name' => $request->input('Name'),
        'batchyear' => $request->input('BatchYear'),
        'type_of_student' => $request->input('type_of_student'),
        'course' => $course,
        'major' => $major, 
    ]);

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

    return redirect()->route('encoder.index')->with('success', 'Files uploaded successfully.');
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
        return response()->file($filePath);
    } else {
        return back()->with('error', 'File not found.');
    }
}

public function deleteFile($id)
{
    $file = UploadedFile::findOrFail($id);

    $file->delete();

    return redirect()->back()->with('success', 'File record deleted successfully.');
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