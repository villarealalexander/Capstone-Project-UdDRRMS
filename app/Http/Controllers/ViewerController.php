<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class ViewerController extends Controller
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
    
        return view('viewer.index', compact('students', 'searchQuery', 'category', 'role', 'name'));
    }

}
