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
            $file->move(public_path('uploads/' . $student->name), $fileName);

            UploadedFile::create([
                'student_id' => $student->id,
                'file' => $fileName,
            ]);
        }
    }

    return redirect()->route('encoder.index')->with('success', 'Files uploaded successfully.');
}

    public function studentFiles($name)
    {
        $student = Student::where('name', $name)->firstOrFail();

        $user = auth()->user();

        if ($user && in_array($user->role, ['encoder', 'viewer', 'admin'])) {
            $files = $student->uploadedFiles;
            return view('encoder.student_files', compact('files', 'name'));
        } else {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }
    }

    public function viewFile($id)
    {
        $file = UploadedFile::findOrFail($id);

        if (file_exists($file->filePath())) {
            return response()->file($file->filePath());
        } else {
            return back()->with('error', 'File not found.');
        }
    }

    public function deleteFile($id)
    {
        $file = UploadedFile::findOrFail($id);

        if (file_exists($file->filePath())) {
            unlink($file->filePath());
        }

        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    public function deleteFolders(Request $request)
    {
        $foldersToDelete = $request->input('foldersToDelete', []);

        if (empty($foldersToDelete)) {
            return redirect()->route('encoder.index')->with('error', 'No folders selected for deletion.');
        }

        $students = Student::whereIn('name', $foldersToDelete)->get();

        foreach ($students as $student) {
            foreach ($student->uploadedFiles as $file) {
                if (file_exists($file->filePath())) {
                    unlink($file->filePath());
                }
                $file->delete();
            }
            $student->delete();
        }

        return redirect()->route('encoder.index')->with('success', 'Selected folders and associated files deleted successfully.');
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

        $validatedData = $request->validate([
            'encoder_password' => 'required|string',
        ]);

        if (!Hash::check($encoderPassword, auth()->user()->password)) {
            return redirect()->back()->with('error', 'Incorrect encoder password. Please try again.');
        }

        foreach ($studentIds as $studentId) {
            $student = Student::findOrFail($studentId);

            foreach ($student->uploadedFiles as $file) {
                $filePath = public_path('uploads/' . $student->name . '/' . $file->file);
                if (file_exists($filePath)) {
                    unlink($filePath); 
                }
                $file->delete(); 
            }

            $student->delete();
        }

        // Log activity
        ActivityLogService::log('Delete folders', 'Deleted selected folders: ' . implode(', ', $studentIds));

        return redirect()->route('encoder.index')->with('success', 'Selected folders and associated files deleted successfully.');
    }

    
}