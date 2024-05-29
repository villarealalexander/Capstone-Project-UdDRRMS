<?php

namespace App\Http\Controllers;

use App\Models\Student;
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

        return view('viewer.index', compact('students', 'searchQuery', 'role', 'name', 'sortParams'));
    }

}
