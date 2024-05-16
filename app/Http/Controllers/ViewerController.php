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
    return view('viewer.index', compact('students', 'searchQuery', 'role', 'name', 'sortParams'));
}

}
