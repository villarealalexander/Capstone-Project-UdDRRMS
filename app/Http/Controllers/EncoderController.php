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

        // Set major based on selected course
        if ($course) {
            $major = $request->input('major'); // Use the selected major from dropdown
        }
    } elseif ($request->input('type_of_student') === 'Post Graduate') {
        $course = $request->input('postGradDegrees');
        if ($course === 'Masters') {
            $course = $request->input('mastersCourses');
        } elseif ($course === 'Doctorate') {
            $course = $request->input('doctorateCourses');
        }
        // Set major based on selected course
        if ($course) {
            $major = $request->input('major'); // Use the selected major from dropdown
        }
    }

    // Find existing student or create a new one based on name and batch year
    $student = Student::updateOrCreate([
        'name' => $request->input('Name'),
        'batchyear' => $request->input('BatchYear'),
        'type_of_student' => $request->input('type_of_student'),
        'course' => $course,
        'major' => $major, // Store major in the database
    ]);

    // Handle uploaded files
    if ($request->hasFile('file')) {
        foreach ($request->file('file') as $file) {
            $fileName = $file->getClientOriginalName();

            // Create a directory path based on student's ID
            $studentFolderPath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' .  $student->id . '/');

            // Check if the directory exists, otherwise create it
            if (!file_exists($studentFolderPath)) {
                mkdir($studentFolderPath, 0755, true);
            }

            // Move the uploaded file into the student's directory
            $file->move($studentFolderPath, $fileName);

            // Save file record in database
            UploadedFile::create([
                'student_id' => $student->id,
                'file' => $fileName,
            ]);
        }
    }

    return redirect()->route('encoder.index')->with('success', 'Files uploaded successfully.');
}

public function addFileToStudent(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'file.*' => 'required|file|max:10240|mimes:pdf', // assuming a max size of 10MB for PDF files
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $student = Student::findOrFail($id); // Ensure the student exists

    if ($request->hasFile('file')) {
        foreach ($request->file('file') as $file) {
            $fileName = $file->getClientOriginalName();

        // Create a directory path based on student's ID
        $studentFolderPath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' .  $student->id . '/');

        // Check if the directory exists, otherwise create it
        if (!file_exists($studentFolderPath)) {
            mkdir($studentFolderPath, 0755, true);
        }

        // Move the uploaded file into the student's directory
        $file->move($studentFolderPath, $fileName);

        // Save file record in the database
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

    // Check if the user has appropriate role to access student files
    if ($user && in_array($user->role, ['encoder', 'viewer', 'admin'])) {
        $files = $student->uploadedFiles;
        return view('encoder.student_files', compact('files', 'student'));
    } else {
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}

public function viewFile($id)
{
    // Find the UploadedFile record by ID
    $file = UploadedFile::findOrFail($id);

    // Construct the full file path based on the student's directory and file name
    $filePath = public_path('uploads/' . $file->student->name . '_' . $file->student->batchyear . '_' . $file->student->id . '/' . $file->file);

    // Check if the file exists at the specified path
    if (file_exists($filePath)) {
        // Serve the file as a response
        return response()->file($filePath);
    } else {
        // If file not found, redirect back with an error message
        return back()->with('error', 'File not found.');
    }
}

public function deleteFile($id)
{
    // Find the UploadedFile record by ID
    $file = UploadedFile::findOrFail($id);

    // Construct the full file path based on the student's directory and file name
    $filePath = public_path('uploads/' . $file->student->name . '_' . $file->student->batchyear . '_' . $file->student->id . '/' . $file->file);

    // Check if the file exists at the specified path
    if (file_exists($filePath)) {
        // Attempt to delete the file from the file system
        if (unlink($filePath)) {
            // If file deletion successful, delete the record from the database
            $file->delete();
            return redirect()->back()->with('success', 'File deleted successfully.');
        } else {
            // If file deletion failed, redirect back with an error message
            return redirect()->back()->with('error', 'Failed to delete file from the file system.');
        }
    } else {
        // If file not found at the specified path, delete the record from the database
        $file->delete();
        return redirect()->back()->with('success', 'File record deleted successfully.');
    }
}

public function deleteFolders(Request $request)
{
    $foldersToDelete = $request->input('foldersToDelete', []);

    if (empty($foldersToDelete)) {
        return redirect()->route('encoder.index')->with('error', 'No folders selected for deletion.');
    }

    // Pass the list of student IDs to the confirmation view
    return view('encoder.confirm-delete', ['studentIds' => $foldersToDelete]);
}
public function confirmDelete($id)
{
    $student = Student::findOrFail($id);

    return view('encoder.confirm-delete', compact('student'));
}

public function destroyMultiple(Request $request)
{
    $studentIds = $request->input('studentsToDelete', []);

    if (empty($studentIds)) {
        return redirect()->route('encoder.index')->with('error', 'No folders selected for deletion.');
    }

    $encoderPassword = $request->input('encoder_password');

    // Validate encoder password
    $validatedData = $request->validate([
        'encoder_password' => 'required|string',
    ]);

    // Check if encoder password matches the authenticated user's password
    if (!Hash::check($encoderPassword, auth()->user()->password)) {
        return redirect()->back()->with('error', 'Incorrect encoder password. Please try again.');
    }

    foreach ($studentIds as $studentId) {
        $student = Student::findOrFail($studentId);

        // Delete student files (if any)
        foreach ($student->uploadedFiles as $file) {
            $filePath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' . $student->id . '/' . $file->file);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file->delete();
        }

        // Delete the student record
        $student->delete();
    }

    // Log activity
    ActivityLogService::log('Delete folders', 'Deleted selected folders: ' . implode(', ', $studentIds));

    return redirect()->route('encoder.index')->with('success', 'Selected folders and associated files deleted successfully.');
}
    
}