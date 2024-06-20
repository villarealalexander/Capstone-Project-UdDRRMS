<?php

namespace App\Http\Controllers;

use App\Models\Student;
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

        return view('encoder.index', compact('students', 'searchQuery', 'role', 'name', 'sortParams'));
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);

        ActivityLogService::log('View', 'Viewed student details: ' . $student->name . ' (ID: ' . $student->id . ')');

        return view('encoder.show', compact('student'));
    }

    public function updateDescription(Request $request, UploadedFile $file)
{
    $file->update([
        'description' => $request->description,
    ]);

    return back()->with('success', 'File description updated successfully.');
}
    public function checklist(Request $request)
    {
        ActivityLogService::log('View', 'Accessed the checklist page.');

        // Get the student ID from the request
        $studentId = $request->student_id;
        
        // Fetch the student and their uploaded files
        $student = Student::with('uploadedFiles')->findOrFail($studentId);
        $files = $student->uploadedFiles;

        return view('encoder.checklist', compact('student', 'files'));
    }

    // Upload file area
    public function uploadfile()
    {
        ActivityLogService::log('View', 'Accessed the upload file page.');

        return view('encoder.upload');
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'Name' => 'required|string',
        'BatchYear' => 'required|numeric',
        'type_of_student' => 'required|string|in:Undergraduate,Post Graduate',
        'files.*' => 'required|file|max:10240|mimes:pdf',
        'descriptions.*' => 'required|string|max:255', // Adding validation for descriptions
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

    $currentMonth = date('F');

    $student = Student::updateOrCreate([
        'name' => $request->input('Name'),
        'batchyear' => $request->input('BatchYear'),
        'type_of_student' => $request->input('type_of_student'),
        'course' => $course,
        'major' => $major, // Store major in the database
        'month_uploaded' => $currentMonth,
    ]);

    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $index => $file) {
            $fileName = $file->getClientOriginalName();
            $description = $request->input('descriptions')[$index] ?? '';

            $studentFolderPath = public_path('uploads/' . $student->name . '_' . $student->batchyear . '_' .  $student->id . '/');

            if (!file_exists($studentFolderPath)) {
                mkdir($studentFolderPath, 0755, true);
            }

            $file->move($studentFolderPath, $fileName);

            UploadedFile::create([
                'student_id' => $student->id,
                'file' => $fileName,
                'description' => $description, // Store the description in the database
            ]);
        }
    }

    ActivityLogService::log('Upload', 'Uploaded files for student: ' . $student->name . ' (ID: ' . $student->id . ')');

    return redirect()->route('encoder.index')->with('success', 'Student information and files uploaded successfully.');
}

    public function addFileToStudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file.*' => 'required|file|max:10240|mimes:pdf',
            'description.*' => 'required|string|max:255',
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
                    'description' => $request->input('description'),
                ]);
            }
        }

        ActivityLogService::log('Add file', 'Added files to student: ' . $student->name . ' (ID: ' . $student->id . ')');

        return redirect()->route('student.files', $id)->with('success', 'File uploaded successfully.');
    }
    // End of upload file area

    // View file area
    public function studentFiles($id)
    {
        $student = Student::findOrFail($id);
        $user = auth()->user();
        $role = auth()->user()->role;
        $name = auth()->user()->name;

        if ($user && in_array($user->role, ['encoder', 'viewer', 'admin'])) {
            $files = UploadedFile::where('student_id', $student->id)->withoutTrashed()->get();

            ActivityLogService::log('View', 'Viewed files for student: ' . $student->name . ' (ID: ' . $student->id . ')');

            return view('encoder.student_files', compact('files', 'student' , 'role', 'name'));
        } else {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }
    }

    public function viewFile($id)
    {
        $file = UploadedFile::findOrFail($id);
        $student = $file->student;
        $filePath = 'uploads/' . $student->name . '_' . $student->batchyear . '_' . $student->id . '/' . $file->file;
        $fileUrl = asset($filePath);

        if (file_exists(public_path($filePath))) {
            ActivityLogService::log('View', 'Viewed file: ' . $file->file . ' for student: ' . $student->name . ' (ID: ' . $student->id . ')');

            return view('viewfile', compact('fileUrl', 'student'));
        } else {
            return back()->with('error', 'File not found.');
        }
    }
    // End of view file area

    // Delete file and delete student folder area
    public function confirmStudentDelete(Request $request)
    {
        $selectedStudentIds = $request->input('selected_students', []);

        if (empty($selectedStudentIds)) {
            return redirect()->route('encoder.index')->with('error', 'No folders selected for archive.');
        }

        ActivityLogService::log('View', 'Accessed confirm archive student folders page.');

        return view('encoder.confirm-student-delete', compact('selectedStudentIds'));
    }

    public function deleteFile($id)
    {
        $file = UploadedFile::findOrFail($id);

        // Soft delete the file record
        $file->delete();

        ActivityLogService::log('Archive', 'Archived file: ' . $file->file . ' (ID: ' . $file->id . ') for student: ' . $file->student->name);

        return redirect()->back()->with('success', 'File archived successfully.');
    }

    public function destroyMultiple(Request $request)
    {
        $studentIds = $request->input('studentsToDelete', []);
        $encoderPassword = $request->input('encoder_password');

        $validatedData = $request->validate([
            'encoder_password' => 'required|string',
        ]);

        if (!Hash::check($encoderPassword, auth()->user()->password)) {
            return redirect()->back()->with('error', 'Incorrect encoder password. Please try again.');
        }

        foreach ($studentIds as $studentId) {
            $student = Student::withTrashed()->findOrFail($studentId);

            $student->uploadedFiles()->delete();
            $student->delete();
        }

        ActivityLogService::log('Archive', 'Archived selected student folders: ' . implode(', ', $studentIds));

        return redirect()->route('encoder.index')->with('success', 'Selected folders and associated files archive successfully.');
    }
    // End of delete file and delete student folder area

    // Archive function area
    public function restoreFile($id)
    {
        $file = UploadedFile::onlyTrashed()->findOrFail($id);

        $file->restore();

        ActivityLogService::log('Restore', 'Restored file: ' . $file->file . ' (ID: ' . $file->id . ') for student: ' . $file->student->name);

        return redirect()->back()->with('success', 'File restored successfully.');
    }

    public function showArchivedFiles($studentId)
    {
        $student = Student::withTrashed()->findOrFail($studentId);
        $archivedFiles = UploadedFile::onlyTrashed()->where('student_id', $studentId)->get();
        $role = auth()->user()->role;
        $name = auth()->user()->name;

        ActivityLogService::log('View', 'Viewed archived files for student: ' . $student->name . ' (ID: ' . $student->id . ')');

        return view('encoder.archived-files', compact('student', 'archivedFiles', 'role', 'name'));
    }

    public function restore($id)
    {
        $restoredStudent = Student::onlyTrashed()->findOrFail($id);

        $restoredStudent->restore();

        $restoredFiles = UploadedFile::onlyTrashed()->where('student_id', $restoredStudent->id)->get();

        foreach ($restoredFiles as $file) {
            $file->restore();
        }

        ActivityLogService::log('Restore', 'Restored student: ' . $restoredStudent->name . ' (ID: ' . $restoredStudent->id . ') and associated files.');

        return redirect()->route('encoder.archives')->with('success', 'Student and associated files restored successfully.');
    }

    public function archives()
    {
        $archivedStudents = Student::onlyTrashed()->get();
        $role = auth()->user()->role;
        $name = auth()->user()->name;

        ActivityLogService::log('View', 'Accessed archived students.');

        return view('encoder.archives', compact('archivedStudents', 'role', 'name'));
    }
    // End of archive function area
}
